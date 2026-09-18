import { Link, usePage } from '@inertiajs/react';
import { BookOpen, LayoutGrid, Users } from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index as exams } from '@/routes/admin/exams';
import { index as trainees } from '@/routes/admin/trainees';
import { dashboard as traineeDashboard } from '@/routes/trainee';
import type { NavItem } from '@/types';

export function AppSidebar() {
    const { auth } = usePage().props;
    const isLawyer = auth.user.role === 'lawyer';

    const mainNavItems: NavItem[] = isLawyer
        ? [
              {
                  title: 'لوحة التحكم',
                  href: adminDashboard(),
                  icon: LayoutGrid,
              },
              {
                  title: 'المتدربون',
                  href: trainees(),
                  icon: Users,
              },
              {
                  title: 'الاختبارات',
                  href: exams(),
                  icon: BookOpen,
              },
          ]
        : [
              {
                  title: 'لوحة المتدرب',
                  href: traineeDashboard(),
                  icon: LayoutGrid,
              },
          ];

    return (
        <Sidebar collapsible="icon" variant="inset" side="right">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link
                                href={
                                    isLawyer
                                        ? adminDashboard()
                                        : traineeDashboard()
                                }
                                prefetch
                            >
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
