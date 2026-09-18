import { Link, usePage } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';
import { dashboard } from '@/routes/trainee';
import { logout } from '@/routes';

export default function ExamLayout({
    children,
}: {
    children: React.ReactNode;
}) {
    const { auth, name } = usePage().props;

    return (
        <div className="bg-background min-h-svh">
            <header className="flex items-center justify-between border-b px-4 py-3">
                <Link
                    href={dashboard()}
                    className="flex items-center gap-2 text-sm font-medium"
                >
                    <AppLogoIcon className="size-6 fill-current" />
                    {name}
                </Link>
                <div className="flex items-center gap-4 text-sm">
                    <span>{auth.user?.name}</span>
                    <Link href={logout()} method="post" as="button">
                        تسجيل الخروج
                    </Link>
                </div>
            </header>
            <main>{children}</main>
        </div>
    );
}
