import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';

interface User {
    id: number;
    name: string;
    email: string;
    is_guest: boolean;
    xp: number;
    role: {
        id: number;
        name: string;
        display_name: string;
    } | null;
    level: {
        id: number;
        name: string;
        level_number: number;
        color: string;
        icon: string;
    } | null;
    achievements_count: number;
    streaks_count: number;
    created_at: string;
}

interface UsersProps {
    users: {
        data: User[];
        current_page: number;
        last_page: number;
        total: number;
    };
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Users',
        href: '/dashboard/users',
    },
];

export default function Users({ users }: UsersProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold">Users</h1>
                        <p className="text-muted-foreground">
                            Manage and view all registered users
                        </p>
                    </div>
                    <Badge variant="secondary" className="text-sm">
                        Total: {users.total}
                    </Badge>
                </div>

                <div className="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead className="w-[50px]">Avatar</TableHead>
                                <TableHead>Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Role</TableHead>
                                <TableHead>Level</TableHead>
                                <TableHead className="text-center">XP</TableHead>
                                <TableHead className="text-center">Achievements</TableHead>
                                <TableHead className="text-center">Streaks</TableHead>
                                <TableHead>Type</TableHead>
                                <TableHead>Joined</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {users.data.map((user) => (
                                <TableRow key={user.id}>
                                    <TableCell>
                                        <Avatar className="h-8 w-8">
                                            <AvatarFallback className="text-xs">
                                                {user.name.substring(0, 2).toUpperCase()}
                                            </AvatarFallback>
                                        </Avatar>
                                    </TableCell>
                                    <TableCell className="font-medium">
                                        {user.name}
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {user.email}
                                    </TableCell>
                                    <TableCell>
                                        {user.role ? (
                                            <Badge variant="outline">
                                                {user.role.display_name}
                                            </Badge>
                                        ) : (
                                            <Badge variant="secondary">No Role</Badge>
                                        )}
                                    </TableCell>
                                    <TableCell>
                                        {user.level ? (
                                            <div className="flex items-center gap-2">
                                                <span style={{ color: user.level.color }}>
                                                    {user.level.icon}
                                                </span>
                                                <span className="text-sm">
                                                    {user.level.name}
                                                </span>
                                            </div>
                                        ) : (
                                            <Badge variant="secondary">No Level</Badge>
                                        )}
                                    </TableCell>
                                    <TableCell className="text-center">
                                        <Badge variant="outline">
                                            {user.xp || 0} XP
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-center">
                                        <Badge variant="secondary">
                                            {user.achievements_count}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-center">
                                        <Badge variant="secondary">
                                            {user.streaks_count}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <Badge 
                                            variant={user.is_guest ? "outline" : "default"}
                                        >
                                            {user.is_guest ? "Guest" : "Registered"}
                                        </Badge>
                                    </TableCell>
                                    <TableCell className="text-muted-foreground">
                                        {new Date(user.created_at).toLocaleDateString()}
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>

                {users.last_page > 1 && (
                    <div className="flex items-center justify-between px-2">
                        <div className="text-sm text-muted-foreground">
                            Page {users.current_page} of {users.last_page}
                        </div>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}