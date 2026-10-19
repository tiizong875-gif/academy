<?php
namespace App\Controllers\Admin;

use App\Models\Category;
use App\Models\Course;
use App\Helpers\Str;
use App\Helpers\Upload;

class CategoryAdminController extends AdminController {
    public function index(): void {
        $categoryModel = new Category();
        $categories = $categoryModel->getWithCourseCount();

        $this->renderAdmin('admin/categories/index', [
            'categories' => $categories,
            'title'      => 'គ្រប់គ្រងប្រភេទវគ្គសិក្សា (Categories)'
        ]);
    }

    public function store(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $name = trim((string)$this->request->input('name', ''));
        $nameKh = trim((string)$this->request->input('name_kh', ''));
        $icon = trim((string)$this->request->input('icon', 'bi-code-slash'));
        $description = trim((string)$this->request->input('description', ''));
        $sortOrder = (int)$this->request->input('sort_order', 0);

        if (empty($name) || empty($nameKh)) {
            $this->withError('សូមបំពេញឈ្មោះប្រភេទជាអក្សរឡាតាំង និងអក្សរខ្មែរ');
            $this->redirectBack();
            return;
        }

        $slug = Str::slug($name);
        $categoryModel = new Category();
        if ($categoryModel->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $catId = $categoryModel->create([
            'name'        => $name,
            'name_kh'     => $nameKh,
            'slug'        => $slug,
            'icon'        => $icon,
            'description' => $description ?: null,
            'sort_order'  => $sortOrder,
            'is_active'   => 1
        ]);

        $this->logAction('CREATE_CATEGORY', 'Category', $catId, "បានបង្កើតប្រភេទវគ្គសិក្សា៖ {$nameKh}");
        $this->withSuccess('បានបង្កើតប្រភេទវគ្គសិក្សាដោយជោគជ័យ!');
        $this->redirect('/admin/categories');
    }

    public function update(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $categoryModel = new Category();
        $category = $categoryModel->find($id);

        if (!$category) {
            $this->render('pages/404');
            return;
        }

        $name = trim((string)$this->request->input('name', ''));
        $nameKh = trim((string)$this->request->input('name_kh', ''));
        $icon = trim((string)$this->request->input('icon', 'bi-code-slash'));
        $description = trim((string)$this->request->input('description', ''));
        $sortOrder = (int)$this->request->input('sort_order', 0);
        $isActive = $this->request->input('is_active') ? 1 : 0;

        $categoryModel->update($id, [
            'name'        => $name,
            'name_kh'     => $nameKh,
            'icon'        => $icon,
            'description' => $description ?: null,
            'sort_order'  => $sortOrder,
            'is_active'   => $isActive
        ]);

        $this->logAction('UPDATE_CATEGORY', 'Category', $id, "បានកែប្រែប្រភេទវគ្គសិក្សា៖ {$nameKh}");
        $this->withSuccess('បានរក្សាទុកការកែប្រែប្រភេទវគ្គសិក្សា!');
        $this->redirect('/admin/categories');
    }

    public function delete(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $categoryModel = new Category();
        $category = $categoryModel->find($id);

        if (!$category) {
            $this->render('pages/404');
            return;
        }

        // Check if courses exist under this category
        $courseModel = new Course();
        $courseCount = $courseModel->count("category_id = :cid", ['cid' => $id]);

        if ($courseCount > 0) {
            $this->withError("មិនអាចលុបប្រភេទនេះបានទេ ពីព្រោះមានវគ្គសិក្សាចំនួន {$courseCount} កំពុងស្ថិតនៅក្រោមប្រភេទនេះ។ សូមប្តូរប្រភេទវគ្គសិក្សាទាំងនោះជាមុនសិន!");
            $this->redirect('/admin/categories');
            return;
        }

        $categoryModel->delete($id);
        $this->logAction('DELETE_CATEGORY', 'Category', $id, "បានលុបប្រភេទវគ្គសិក្សា៖ {$category['name_kh']}");
        $this->withSuccess('បានលុបប្រភេទវគ្គសិក្សាដោយជោគជ័យ!');
        $this->redirect('/admin/categories');
    }
}
