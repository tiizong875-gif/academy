<?php
namespace App\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\Review;
use App\Models\Faq;

class HomeController extends Controller {
    public function index(): void {
        $courseModel = new Course();
        $categoryModel = new Category();
        $userModel = new User();
        $reviewModel = new Review();

        $featuredCourses = $courseModel->getFeatured(6);
        $latestCourses = $courseModel->getLatest(6);
        $popularCourses = $courseModel->getPopular(6);
        $categories = $categoryModel->getWithCourseCount();

        // Learning stats
        $stats = [
            'students'  => max(150, $userModel->count("role = 'student'")),
            'courses'   => $courseModel->count("status = 'published'"),
            'instructors' => max(5, $userModel->count("role = 'instructor'")),
            'satisfaction' => '99%'
        ];

        // Testimonials
        $testimonials = [
            [
                'name'    => 'គឹម ហេង',
                'role'    => 'Junior Frontend Developer',
                'avatar'  => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=120&auto=format&fit=crop&q=80',
                'content' => 'ខ្ញុំបានចាប់ផ្ដើមរៀន Web Development នៅ ក្តឹបកូដ Academy តាំងពីគ្មានមូលដ្ឋានសោះ។ បច្ចុប្បន្នខ្ញុំបានជាប់ការងារជា Junior Developer នៅក្រុមហ៊ុន IT មួយនៅភ្នំពេញ។ សូមអរគុណលោកគ្រូខ្លាំងណាស់!'
            ],
            [
                'name'    => 'លីណា ស្រីពេជ្រ',
                'role'    => 'Computer Science Student',
                'avatar'  => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&auto=format&fit=crop&q=80',
                'content' => 'មេរៀនជាភាសាខ្មែរ ងាយយល់ ពន្យល់ក្បោះក្បាយពីមូលដ្ឋានដល់កម្រិតអនុវត្តគម្រោងពិត។ វីដេអូកម្រិត HD ច្បាស់ និងមានឯកសារកូដគំរូទាញយកទៀតផង។'
            ],
            [
                'name'    => 'ចាន់ សុភ័ក្រ្ត',
                'role'    => 'Freelance Developer',
                'avatar'  => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=120&auto=format&fit=crop&q=80',
                'content' => 'វគ្គសិក្សា PHP & MySQL Architecture ធ្វើឱ្យខ្ញុំយល់ច្បាស់ពីរបៀបសរសេរកូដស្អាត មានសុវត្ថិភាព និងតាមស្តង់ដារវិជ្ជាជីវៈ។ ពិតជាមានតម្លៃលើសពីតម្លៃវគ្គសិក្សា!'
            ]
        ];

        $this->render('home/index', [
            'featuredCourses' => $featuredCourses,
            'latestCourses'   => $latestCourses,
            'popularCourses'  => $popularCourses,
            'categories'      => $categories,
            'stats'           => $stats,
            'testimonials'    => $testimonials,
            'title'           => 'ក្តឹបកូដ Academy — រៀនធ្វើ រៀនបង្កើត រៀនដើម្បីជីវិត'
        ]);
    }
}
