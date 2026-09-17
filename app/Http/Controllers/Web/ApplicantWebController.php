<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\CouncilQuestionsService;
use Illuminate\Http\Request;

class ApplicantWebController extends Controller
{
    public function show($id)
    {
        $applicant = Registration::findOrFail($id);

        // Prepare interview questions for the view (MVC: controller supplies data, no JS fetch needed)
        if (!empty($applicant->interview_questions)) {
            $questions = is_string($applicant->interview_questions) ? json_decode($applicant->interview_questions, true) : $applicant->interview_questions;
            if (!is_array($questions)) {
                $questions = CouncilQuestionsService::getAllForCouncil($applicant->council ?? 'Academic Council');
            }
        } else {
            $questions = CouncilQuestionsService::getAllForCouncil($applicant->council ?? 'Academic Council');
        }

        $notes = $applicant->interview_notes ?? [];

        return view('applicants.show', compact('applicant', 'questions', 'notes'));
    }

    public function update(Request $request, $id)
    {
        $applicant = Registration::findOrFail($id);
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'college' => 'sometimes|string|max:255',
            'level' => 'sometimes|string|max:50',
            'council' => 'sometimes|nullable|string|max:100',
            'event_type' => 'sometimes|nullable|string|max:100',
            'ushered_by' => 'sometimes|nullable|string|max:255',
            'interviewed_by' => 'sometimes|nullable|string|max:255',
            'interview_time' => 'sometimes|nullable|string',
            'rating' => 'sometimes|nullable|string|max:50',
            'notes' => 'sometimes|nullable|string',
            'interview_questions' => 'sometimes|nullable|array',
            'interview_notes' => 'sometimes|nullable|array',
        ]);

        // Handle interview_questions/notes encoding
        if (isset($data['interview_questions']) && is_array($data['interview_questions'])) {
            $data['interview_questions'] = json_encode($data['interview_questions'], JSON_UNESCAPED_UNICODE);
        }
        if (isset($data['interview_notes']) && is_array($data['interview_notes'])) {
            $data['interview_notes'] = json_encode($data['interview_notes'], JSON_UNESCAPED_UNICODE);
        }
        if (array_key_exists('interview_time', $data) && empty($data['interview_time'])) {
            $data['interview_time'] = null;
        }

        $applicant->fill($data);
        $applicant->save();

        if ($request->expectsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Updated successfully', 'data' => $applicant]);
        }
        return redirect()->route('applicants.show', $applicant->id)->with('success', 'Updated');
    }

    public function updateRating(Request $request, $id)
    {
        return $this->update($request, $id);
    }
}
