import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Form, setLayoutProps } from '@inertiajs/react';
import { dashboard as adminDashboard } from '@/routes/admin';
import { edit, index, update } from '@/routes/admin/trainees';

type Props = {
    trainee: {
        id: number;
        name: string;
        email: string;
    };
};

export default function EditTrainee({ trainee }: Props) {
    setLayoutProps({
        breadcrumbs: [
            { title: 'لوحة التحكم', href: adminDashboard() },
            { title: 'المتدربون', href: index() },
            { title: 'تعديل', href: edit(trainee) },
        ],
    });

    return (
        <div className="flex flex-col gap-6 p-4">
            <Heading
                title="تعديل متدرب"
                description="اترك كلمة المرور فارغة إذا لم ترد تغييرها."
            />
            <Form {...update.form(trainee)} className="grid max-w-xl gap-4">
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-2">
                            <Label htmlFor="name">الاسم</Label>
                            <Input
                                id="name"
                                name="name"
                                defaultValue={trainee.name}
                                required
                            />
                            <InputError message={errors.name} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="email">البريد الإلكتروني</Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                defaultValue={trainee.email}
                                required
                            />
                            <InputError message={errors.email} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="password">كلمة المرور</Label>
                            <PasswordInput id="password" name="password" />
                            <InputError message={errors.password} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="password_confirmation">
                                تأكيد كلمة المرور
                            </Label>
                            <PasswordInput
                                id="password_confirmation"
                                name="password_confirmation"
                            />
                        </div>
                        <Button type="submit" disabled={processing}>
                            {processing && <Spinner />}
                            تحديث
                        </Button>
                    </>
                )}
            </Form>
        </div>
    );
}
