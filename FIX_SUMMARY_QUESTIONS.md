# ✅ Fix Summary: Questions Not Displaying / Data Mix-up

## 🧩 The Issue
The data coming from the backend had a mix-up:
1. `interview_questions` field contained **Notes** (a JSON object) instead of **Questions** (an array).
2. The Frontend was expecting an array of questions, but got an object of notes, so it crashed or showed nothing.
3. The original static fallback questions were deleted, causing errors.

## 🛠️ The Fix

### 1. Added Static Questions to Frontend
I ported all questions from `backend/council_questions.php` into a robust JavaScript constant `STATIC_COUNCIL_QUESTIONS`. 
This ensures the frontend **always** has the correct question text, category, and difficulty info, even if the API acts up.

### 2. Smart Data Parsing
Updated `loadApplicant` with intelligent logic:
- **Questions:** It now fetches questions from your local static JS based on the applicant's council (e.g., "Backend Development").
- **Notes:** It checks **both** fields:
    - `interview_notes` (The correct new field)
    - `interview_questions` (The field where legacy data/notes were accidentally stored)
  
  It merges notes from both sources, so **no data is lost**.

### 3. Safety Fallbacks
- `getCouncilQuestions()` helper function handles council name mismatches (e.g. matching "Backend Development" to "Backend Development Council").
- Removed references to deleted static arrays to prevent `ReferenceError`.

## 🚀 How to Test
1. Reload the Applicant Details page.
2. You should see the correct questions for "Backend Development".
3. Any previously saved notes (even if saved in the wrong column) should appear in the textareas.
4. New notes will be saved correctly to `interview_notes`.

## 📂 Files Updated
- `frontend/applicant_details_page.html`
