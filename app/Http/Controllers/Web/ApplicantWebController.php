<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\CouncilQuestionsService;

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
}
