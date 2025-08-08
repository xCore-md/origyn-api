import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Plus, Edit, Trash2, Eye, Users } from 'lucide-react';

interface Achievement {
    id: number;
    key: string;
    name: string;
    description: string;
    icon: string | null;
    color: string;
    xp_reward: number;
    type: 'streak' | 'level' | 'general';
    criteria: any;
    is_hidden: boolean;
    order: number;
    users_count: number;
    created_at: string;
}

interface AchievementsProps {
    achievements: {
        data: Achievement[];
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
        title: 'Achievements',
        href: '/dashboard/achievements',
    },
];

export default function Achievements({ achievements }: AchievementsProps) {
    const handleDelete = (achievement: Achievement) => {
        if (confirm(`Are you sure you want to delete "${achievement.name}"?`)) {
            router.delete(`/dashboard/achievements/${achievement.id}`);
        }
    };

    const getTypeColor = (type: string) => {
        switch (type) {
            case 'streak': return 'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300';
            case 'level': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300';
            case 'general': return 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300';
            default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300';
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Achievements Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between">
                            <div>
                                <CardTitle>Achievements</CardTitle>
                                <CardDescription>
                                    Manage game achievements and rewards
                                </CardDescription>
                            </div>
                            <div className="flex items-center gap-2">
                                <Badge variant="secondary" className="text-sm">
                                    Total: {achievements.total}
                                </Badge>
                                <Link href="/dashboard/achievements/create">
                                    <Button>
                                        <Plus className="h-4 w-4 mr-2" />
                                        Create Achievement
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div className="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead className="w-[50px]">Order</TableHead>
                                        <TableHead className="w-[60px]">Icon</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Key</TableHead>
                                        <TableHead>Type</TableHead>
                                        <TableHead className="text-center">XP</TableHead>
                                        <TableHead className="text-center">Users</TableHead>
                                        <TableHead className="text-center">Hidden</TableHead>
                                        <TableHead className="w-[120px]">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {achievements.data.map((achievement) => (
                                        <TableRow key={achievement.id}>
                                            <TableCell className="font-medium">
                                                {achievement.order}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center justify-center">
                                                    {achievement.icon ? (
                                                        <span className="text-xl" style={{ color: achievement.color }}>
                                                            {achievement.icon}
                                                        </span>
                                                    ) : (
                                                        <div 
                                                            className="w-6 h-6 rounded"
                                                            style={{ backgroundColor: achievement.color }}
                                                        />
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div>
                                                    <div className="font-medium">{achievement.name}</div>
                                                    <div className="text-sm text-muted-foreground line-clamp-1">
                                                        {achievement.description}
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell className="font-mono text-sm">
                                                {achievement.key}
                                            </TableCell>
                                            <TableCell>
                                                <Badge className={getTypeColor(achievement.type)}>
                                                    {achievement.type}
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <Badge variant="outline">
                                                    {achievement.xp_reward} XP
                                                </Badge>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex items-center justify-center gap-1">
                                                    <Users className="h-4 w-4" />
                                                    <span>{achievement.users_count}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {achievement.is_hidden ? (
                                                    <Badge variant="secondary">Hidden</Badge>
                                                ) : (
                                                    <Badge variant="outline">Visible</Badge>
                                                )}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Link href={`/dashboard/achievements/${achievement.id}`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Eye className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Link href={`/dashboard/achievements/${achievement.id}/edit`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Edit className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Button 
                                                        variant="ghost" 
                                                        size="sm"
                                                        onClick={() => handleDelete(achievement)}
                                                        className="text-red-600 hover:text-red-700 hover:bg-red-50"
                                                    >
                                                        <Trash2 className="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        </div>

                        {achievements.last_page > 1 && (
                            <div className="flex items-center justify-between px-2 mt-4">
                                <div className="text-sm text-muted-foreground">
                                    Page {achievements.current_page} of {achievements.last_page}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}