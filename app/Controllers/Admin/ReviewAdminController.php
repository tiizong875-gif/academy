<?php
namespace App\Controllers\Admin;

use App\Models\Review;
use App\Models\Course;

class ReviewAdminController extends AdminController {
    public function index(): void {
        $reviewModel = new Review();
        $page = max(1, (int)$this->request->input('page', 1));

        $reviewsData = $reviewModel->getAllReviews($page, 15);

        $this->renderAdmin('admin/reviews/index', [
            'reviews'    => $reviewsData['items'],
            'pagination' => $reviewsData,
            'title'      => 'គ្រប់គ្រងមតិវាយតម្លៃ (Course Reviews Moderation)'
        ]);
    }

    public function toggleApproval(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $reviewModel = new Review();
        $review = $reviewModel->find($id);

        if (!$review) {
            $this->render('pages/404');
            return;
        }

        $newVal = $review['is_approved'] ? 0 : 1;
        $reviewModel->update($id, ['is_approved' => $newVal]);

        // Recalculate course rating
        $courseModel = new Course();
        $approvedReviews = $reviewModel->getForCourse($review['course_id']);
        $totalStars = array_sum(array_column($approvedReviews, 'rating'));
        $count = count($approvedReviews);
        $avg = $count > 0 ? round($totalStars / $count, 2) : 5.0;

        $courseModel->update($review['course_id'], [
            'average_rating' => $avg,
            'review_count'   => $count
        ]);

        $statusText = $newVal ? 'អនុម័ត (Approved)' : 'បិទមិនឱ្យបង្ហាញ (Hidden)';
        $this->logAction('MODERATE_REVIEW', 'Review', $id, "បានប្តូរស្ថានភាពមតិវាយតម្លៃ ID #{$id} ទៅជា {$statusText}");
        $this->withSuccess("បានប្តូរស្ថានភាពមតិទៅជា {$statusText}!");
        $this->redirect('/admin/reviews');
    }

    public function delete(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $reviewModel = new Review();
        $review = $reviewModel->find($id);

        if ($review) {
            $courseId = $review['course_id'];
            $reviewModel->delete($id);

            // Recalculate course rating
            $courseModel = new Course();
            $approvedReviews = $reviewModel->getForCourse($courseId);
            $totalStars = array_sum(array_column($approvedReviews, 'rating'));
            $count = count($approvedReviews);
            $avg = $count > 0 ? round($totalStars / $count, 2) : 5.0;

            $courseModel->update($courseId, [
                'average_rating' => $avg,
                'review_count'   => $count
            ]);

            $this->logAction('DELETE_REVIEW', 'Review', $id, "បានលុបមតិវាយតម្លៃ ID #{$id}");
            $this->withSuccess('បានលុបមតិវាយតម្លៃដោយជោគជ័យ!');
        }

        $this->redirect('/admin/reviews');
    }
}
