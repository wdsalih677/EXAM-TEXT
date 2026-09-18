import { Head } from '@inertiajs/react';
import TextLink from '@/components/text-link';
import { login } from '@/routes';

export default function Register() {
    return (
        <>
            <Head title="التسجيل غير متاح" />
            <p className="text-muted-foreground text-center text-sm">
                التسجيل العام مغلق. يتولى المحامي إنشاء حسابات المتدربين.
            </p>
            <p className="mt-4 text-center text-sm">
                <TextLink href={login()}>العودة لتسجيل الدخول</TextLink>
            </p>
        </>
    );
}

Register.layout = {
    title: 'التسجيل غير متاح',
    description: 'لا يمكن إنشاء حساب من هذه الصفحة.',
};
