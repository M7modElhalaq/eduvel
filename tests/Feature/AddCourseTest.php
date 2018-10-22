<?php

namespace Tests\Feature;

use App\Courses;
use App\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCourseTest extends TestCase
{
    public function test_can_add_course()
    {
        $this->actingAs(User::find(2));

        $response = $this->post('dashboard/courses/store', [
            'course_title' => 'Persius delenit has cu',
            'teacher_name' => 'Teacher name',
            'course_start' => '2018-07-07',
            'course_expire' => '2018-08-08',
            'course_price' => 150,
            'course_discount_price' => 50,
            'course_image' => UploadedFile::fake()->image('any_image.jpg'),
            'course_video' => 'https://www.youtube.com/watch?v=LDgd_gUcqCw',
            'course_description' => 'Per consequat adolescens ex, cu nibh commune temporibus vim, ad sumo viris eloquentiam sed. Mea appareat omittantur eloquentiam ad, nam ei quas oportere democritum. Prima causae admodum id est, ei timeam inimicus sed. Sit an meis aliquam, cetero inermis vel ut. An sit illum euismod facilisis, tamquam vulputate pertinacia eum at.',
            'category_id' => '1',
            'coupon_code' => 'coupon code',
            'coupon_code_discount_price' => '50',
            'whats_includes' => 'Mobile support, Lesson archive, Mobile support, Tutor chat, Course certificate',
            'course_time' => '1h 30min',
            'what_will_you_learn_title' => ["Suas summo id sed erat erant oporteat", "Illud singulis indoctum ad sed", "Alterum bonorum mentitum an mel"],
            'what_will_you_learn_description' => ["Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus.", "Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus.", "Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus."],

            'video_title' => ["Health Science", "Health and Social Care", "Health Science", "Health and Social Care"],
            'video_category' => ["Introdocution", "Generative Modeling Review", "Variational Autoencoders", "Gaussian Mixture Model Review"],
            'video_url' => ["https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw"],
        ]);

        $response->assertRedirect('dashboard/courses');
        $response->assertSessionHas('success');

        $course = Courses::latest()->first();

        $this->assertEquals($course->course_title, 'Persius delenit has cu');
        $this->assertEquals($course->teacher_name, 'Teacher name');
        $this->assertEquals($course->course_start, '2018-07-07');
        $this->assertEquals($course->course_expire, '2018-08-08');
        $this->assertEquals($course->course_price, 150);
        $this->assertEquals($course->course_discount_price, 50);
        $this->assertStringEndsWith('.jpg', $course->course_image);
        $this->assertEquals($course->course_video, 'https://www.youtube.com/watch?v=LDgd_gUcqCw');
        $this->assertEquals($course->course_description, 'Per consequat adolescens ex, cu nibh commune temporibus vim, ad sumo viris eloquentiam sed. Mea appareat omittantur eloquentiam ad, nam ei quas oportere democritum. Prima causae admodum id est, ei timeam inimicus sed. Sit an meis aliquam, cetero inermis vel ut. An sit illum euismod facilisis, tamquam vulputate pertinacia eum at.');
        $this->assertEquals($course->category_id, '1');
        $this->assertEquals($course->coupon_code, 'coupon code');
        $this->assertEquals($course->coupon_code_discount_price, '50');
        $this->assertEquals($course->whats_includes, 'Mobile support, Lesson archive, Mobile support, Tutor chat, Course certificate');
        $this->assertEquals($course->isActive, 0);
        $this->assertEquals($course->course_time, '1h 30min');
        $this->assertEquals($course->what_will_you_learn_title, json_encode(["Suas summo id sed erat erant oporteat", "Illud singulis indoctum ad sed", "Alterum bonorum mentitum an mel"]));
        $this->assertEquals($course->what_will_you_learn_description, json_encode(["Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus.", "Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus.", "Ut unum diceret eos, mel cu velit principes, ut quo inani dolorem mediocritatem. Mea in justo posidonium necessitatibus."]));

        $this->assertEquals($course->courses_files[0]->video_title, json_encode(["Health Science", "Health and Social Care", "Health Science", "Health and Social Care"]));
        $this->assertEquals($course->courses_files[0]->video_category, json_encode(["Introdocution", "Generative Modeling Review", "Variational Autoencoders", "Gaussian Mixture Model Review"]));
        $this->assertEquals($course->courses_files[0]->video_url, json_encode(["https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw", "https://www.youtube.com/watch?v=LDgd_gUcqCw"]));
    }

    public function test_course_validated()
    {
        $this->actingAs(User::find(2));

        $response = $this->post('dashboard/courses/store', []);

        $response->assertSessionHasErrors('course_title');
        $response->assertSessionHasErrors('teacher_name');
        $response->assertSessionHasErrors('course_start');
        $response->assertSessionHasErrors('course_price');
        $response->assertSessionHasErrors('course_image');
        $response->assertSessionHasErrors('course_video');
        $response->assertSessionHasErrors('course_description');
        $response->assertSessionHasErrors('category_id');
        $response->assertSessionHasErrors('course_time');
        $response->assertSessionHasErrors('what_will_you_learn_title');
        $response->assertSessionHasErrors('what_will_you_learn_description');
    }
}
