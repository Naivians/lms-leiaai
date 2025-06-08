<?php

namespace Database\Factories;

use App\Models\Classes;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class CourseModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $courseNames = [
            'PPL', 'CPL', 'ATPL', 'IR', 'ME',
        ];
        $courseCodes = [
            'PPL101', 'CPL202', 'ATPL303', 'IR404', 'ME505',
        ];
        $courseDescriptions = [
            'Private Pilot License Course',
            'Commercial Pilot License Course',
            'Airline Transport Pilot License Course',
            'Instrument Rating Course',
            'Multi-Engine Rating Course',
        ];

        // foreach($courseNames as $index => $courseName){
        //     \App\Models\CourseModel::create([
        //         'course_name' => $courseName,
        //         'course_code' => $courseCodes[$index],
        //         'course_description' => $courseDescriptions[$index],
        //     ]);
        // }
    }
}
