<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;

class CourseReviews extends Component
{
    use WithPagination;

    public Course $course;
    public array $reviewStats;

    #[Computed]
    public function reviews()
    {
        return $this->course->publishedReviews()
            ->with('user')
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.course-reviews');
    }
}
