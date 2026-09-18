import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Form } from '@inertiajs/react';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, index, store } from '@/routes/admin/trainees';

export default function CreateTrainee() {
    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="إضافة متدرب"
                description="يُنشأ حساب المتدرب بدور متدرب تلقائياً."
            />
            <Form
                {...store.form()}
                className="grid max-w-xl gap-4"
                resetOnSuccess={['password', 'password_confirmation']}
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="name">الاسم</Label>
                            <Input id="name" name="name" required />
                            <InputError message={errors.name} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="email">البريد الإلكتروني</Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                required
                            />
                            <InputError message={errors.email} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="password">كلمة المرور</Label>
                            <PasswordInput id="password" name="password" required />
                            <InputError message={errors.password} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation">
                                تأكيد كلمة المرور
                            </Label>
                            <PasswordInput
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                            />
                        </div>
                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner />}
                            حفظ
                        </Button>
                    </>
                )}
            </Form>
        </div>
    );
}

CreateTrainee.layout = {
    breadcrumbs: [
        { title: 'لوحة التحكم', href: adminDashboard() },
        { title: 'المتدربون', href: index() },
        { title: 'إضافة', href: create() },
    ],
};
