<?php
namespace App\Controllers\Admin;

use App\Models\SiteSetting;
use App\Models\PaymentMethod;
use App\Models\Faq;
use App\Helpers\Upload;

class SettingAdminController extends AdminController {
    public function index(): void {
        $settingModel = new SiteSetting();
        $settings = $settingModel->getAllAsKeyValue();

        $pmModel = new PaymentMethod();
        $paymentMethods = $pmModel->all('sort_order ASC');

        $faqModel = new Faq();
        $faqs = $faqModel->all('sort_order ASC, id ASC');

        $this->renderAdmin('admin/settings/index', [
            'settings'       => $settings,
            'paymentMethods' => $paymentMethods,
            'faqs'           => $faqs,
            'title'          => 'ការកំណត់គេហទំព័រ & វិធីសាស្ត្រទូទាត់ (Website Settings)'
        ]);
    }

    public function updateGeneral(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $settingModel = new SiteSetting();
        $keys = [
            'site_name', 'site_slogan', 'site_email', 'site_phone',
            'site_address', 'facebook_url', 'telegram_url', 'youtube_url', 'tiktok_url'
        ];

        foreach ($keys as $key) {
            $val = trim((string)$this->request->input($key, ''));
            $group = in_array($key, ['facebook_url', 'telegram_url', 'youtube_url', 'tiktok_url']) ? 'social' :
                     (in_array($key, ['site_email', 'site_phone', 'site_address']) ? 'contact' : 'general');
            $settingModel->set($key, $val, $group);
        }

        $this->logAction('UPDATE_SETTINGS', 'SiteSetting', null, 'បានកែប្រែព័ត៌មានទូទៅនៃគេហទំព័រ');
        $this->withSuccess('បានរក្សាទុកការកំណត់ទូទៅដោយជោគជ័យ!');
        $this->redirect('/admin/settings');
    }

    public function updatePaymentMethod(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $id = (int)$id;
        $pmModel = new PaymentMethod();
        $pm = $pmModel->find($id);

        if (!$pm) {
            $this->render('pages/404');
            return;
        }

        $accountName = trim((string)$this->request->input('account_name', ''));
        $accountNumber = trim((string)$this->request->input('account_number', ''));
        $instructions = trim((string)$this->request->input('instructions', ''));
        $isActive = $this->request->input('is_active') ? 1 : 0;

        $updateData = [
            'account_name'   => $accountName,
            'account_number' => $accountNumber,
            'instructions'   => $instructions,
            'is_active'      => $isActive
        ];

        // QR image upload
        $qrFile = $this->request->file('qr_image');
        if ($qrFile && !empty($qrFile['name'])) {
            $upload = Upload::qr($qrFile);
            if ($upload['success']) {
                $updateData['qr_image'] = $upload['filename'];
            } else {
                $this->withError($upload['error']);
                $this->redirect('/admin/settings');
                return;
            }
        }

        $pmModel->update($id, $updateData);
        $this->logAction('UPDATE_PAYMENT_METHOD', 'PaymentMethod', $id, "បានកែប្រែវិធីសាស្ត្រទូទាត់៖ {$pm['name']}");
        $this->withSuccess("បានរក្សាទុកការកំណត់វិធីសាស្ត្រទូទាត់ {$pm['name']}!");
        $this->redirect('/admin/settings');
    }

    public function storeFaq(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $qKh = trim((string)$this->request->input('question_kh', ''));
        $aKh = trim((string)$this->request->input('answer_kh', ''));
        $q = trim((string)$this->request->input('question', ''));
        $a = trim((string)$this->request->input('answer', ''));
        $category = trim((string)$this->request->input('category', 'general'));
        $sortOrder = (int)$this->request->input('sort_order', 0);

        if (empty($qKh) || empty($aKh)) {
            $this->withError('សូមបំពេញសំណួរ និងចម្លើយជាភាសាខ្មែរ');
            $this->redirect('/admin/settings');
            return;
        }

        $faqModel = new Faq();
        $faqModel->create([
            'question_kh' => $qKh,
            'answer_kh'   => $aKh,
            'question'    => $q ?: $qKh,
            'answer'      => $a ?: $aKh,
            'category'    => $category,
            'sort_order'  => $sortOrder,
            'is_active'   => 1
        ]);

        $this->logAction('CREATE_FAQ', 'Faq', null, "បានបង្កើតសំណួរ-ចម្លើយ FAQ ថ្មី");
        $this->withSuccess('បានបន្ថែម FAQ ថ្មីដោយជោគជ័យ!');
        $this->redirect('/admin/settings');
    }

    public function deleteFaq(string $id): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $faqModel = new Faq();
        $faqModel->delete((int)$id);
        $this->logAction('DELETE_FAQ', 'Faq', (int)$id, "បានលុប FAQ ID #{$id}");
        $this->withSuccess('បានលុប FAQ ដោយជោគជ័យ!');
        $this->redirect('/admin/settings');
    }
}
