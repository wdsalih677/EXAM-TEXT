<?php

namespace App\Exceptions;

use App\Models\Attempt;
use Illuminate\Contracts\Debug\ShouldntReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use RuntimeException;

class ExamAttemptException extends RuntimeException implements ShouldntReport
{
    public function __construct(
        string $message,
        public readonly ?string $redirectRoute = null,
        public readonly mixed $redirectParameters = null,
    ) {
        parent::__construct($message);
    }

    public static function alreadyCompleted(Attempt $attempt): self
    {
        return new self(
            'تم إنهاء الاختبار بالفعل.',
            'trainee.attempts.result',
            $attempt,
        );
    }

    public static function unavailable(): self
    {
        return new self(
            'هذه المحاولة غير متاحة.',
            'trainee.dashboard',
        );
    }

    public static function timedOut(Attempt $attempt): self
    {
        if ($attempt->isCompleted()) {
            return new self(
                'انتهى وقت السؤال. تم تسجيل السؤال كغير مجاب.',
                'trainee.attempts.result',
                $attempt,
            );
        }

        return new self(
            'انتهى وقت السؤال. تم تسجيل السؤال كغير مجاب.',
            'trainee.attempts.show',
            $attempt,
        );
    }

    public static function cannotGoBack(Attempt $attempt): self
    {
        return new self(
            'لا يمكنك الرجوع إلى سؤال سابق.',
            'trainee.attempts.show',
            $attempt,
        );
    }

    public static function invalidOption(Attempt $attempt): self
    {
        return new self(
            'الخيار المحدد غير صالح لهذا السؤال.',
            'trainee.attempts.show',
            $attempt,
        );
    }

    public static function answerRequired(Attempt $attempt): self
    {
        return new self(
            'يجب اختيار إجابة قبل الانتقال إلى السؤال التالي.',
            'trainee.attempts.show',
            $attempt,
        );
    }

    public static function examUnavailable(): self
    {
        return new self(
            'هذا الاختبار غير متاح حالياً.',
            'trainee.dashboard',
        );
    }

    public function render(Request $request): RedirectResponse
    {
        Inertia::flash('toast', [
            'type' => 'error',
            'message' => $this->getMessage(),
        ]);

        if ($this->redirectRoute !== null) {
            return redirect()->route($this->redirectRoute, $this->redirectParameters);
        }

        return back();
    }
}
