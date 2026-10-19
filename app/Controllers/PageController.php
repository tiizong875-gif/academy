<?php
namespace App\Controllers;

use App\Models\Faq;
use App\Models\ContactMessage;

class PageController extends Controller {
    public function about(): void {
        $this->render('pages/about', [
            'title' => 'អំពីយើង — ក្តឹបកូដ Academy'
        ]);
    }

    public function contact(): void {
        $this->render('pages/contact', [
            'title' => 'ទំនាក់ទំនង — ក្តឹបកូដ Academy'
        ]);
    }

    public function submitContact(): void {
        if (!$this->validateCsrf()) {
            return;
        }

        $name = trim((string)$this->request->input('name', ''));
        $email = trim((string)$this->request->input('email', ''));
        $phone = trim((string)$this->request->input('phone', ''));
        $subject = trim((string)$this->request->input('subject', ''));
        $message = trim((string)$this->request->input('message', ''));

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            $this->withError('សូមបំពេញគ្រប់ព័ត៌មានដែលចាំបាច់');
            $this->withOld(['name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'message' => $message]);
            $this->redirect('/contact');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->withError('ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវឡើយ');
            $this->withOld(['name' => $name, 'phone' => $phone, 'subject' => $subject, 'message' => $message]);
            $this->redirect('/contact');
            return;
        }

        $contactModel = new ContactMessage();
        $contactModel->create([
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone ?: null,
            'subject' => $subject,
            'message' => $message
        ]);

        $this->withSuccess('សាររបស់អ្នកត្រូវបានផ្ញើដោយជោគជ័យ! យើងខ្ញុំនឹងឆ្លើយតបមកវិញក្នុងពេលឆាប់ៗបំផុត។');
        $this->redirect('/contact');
    }

    public function faq(): void {
        $faqModel = new Faq();
        $faqs = $faqModel->getActive();

        $this->render('pages/faq', [
            'faqs'  => $faqs,
            'title' => 'សំណួរដែលសួរញឹកញាប់ (FAQ) — ក្តឹបកូដ Academy'
        ]);
    }

    public function privacy(): void {
        $this->render('pages/privacy', [
            'title' => 'គោលការណ៍ឯកជនភាព — ក្តឹបកូដ Academy'
        ]);
    }

    public function terms(): void {
        $this->render('pages/terms', [
            'title' => 'លក្ខខណ្ឌប្រើប្រាស់ — ក្តឹបកូដ Academy'
        ]);
    }

    public function notFound(): void {
        http_response_code(404);
        $this->render('pages/404', [
            'title' => 'ទំព័ររកមិនឃើញ (404 Not Found)'
        ]);
    }
}
