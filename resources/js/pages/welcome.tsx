import { Head, Link, usePage } from '@inertiajs/react';
import { dashboard } from '@/routes';
import { login } from '@/routes';

export default function Welcome() {
    const { auth, name } = usePage().props;

    return (
        <>
            <Head title="منصة الاختبارات القانونية" />
            <div className="bg-background flex min-h-screen flex-col items-center justify-center p-6">
                <div className="w-full max-w-lg space-y-6 text-center">
                    <h1 className="text-3xl font-semibold">{name}</h1>
                    <p className="text-muted-foreground leading-7">
                        منصة تدريبية لاختبارات الأحوال الشخصية في الحضانة والنفقة
                        والطلاق والزيارة.
                    </p>
                    {auth.user ? (
                        <Link
                            href={dashboard()}
                            className="bg-primary text-primary-foreground inline-flex rounded-md px-5 py-2 text-sm font-medium"
                        >
                            الدخول إلى لوحة التحكم
                        </Link>
                    ) : (
                        <Link
                            href={login()}
                            className="bg-primary text-primary-foreground inline-flex rounded-md px-5 py-2 text-sm font-medium"
                        >
                            تسجيل الدخول
                        </Link>
                    )}
                </div>
            </div>
        </>
    );
}
