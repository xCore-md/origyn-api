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

interface Level {
    id: number;
    level_number: number;
    name: string;
    description: string | null;
    min_xp: number;
    max_xp: number;
    color: string;
    icon: string | null;
    users_count: number;
    created_at: string;
}

interface LevelsProps {
    levels: {
        data: Level[];
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
        title: 'Levels',
        href: '/dashboard/levels',
    },
];

export default function Levels({ levels }: LevelsProps) {
    const handleDelete = (level: Level) => {
        if (confirm(`Are you sure you want to delete level "${level.name}"?`)) {
            router.delete(`/dashboard/levels/${level.id}`);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Levels Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between">
                            <div>
                                <CardTitle>Levels</CardTitle>
                                <CardDescription>
                                    Manage user progression levels and XP requirements
                                </CardDescription>
                            </div>
                            <div className="flex items-center gap-2">
                                <Badge variant="secondary" className="text-sm">
                                    Total: {levels.total}
                                </Badge>
                                <Link href="/dashboard/levels/create">
                                    <Button>
                                        <Plus className="h-4 w-4 mr-2" />
                                        Create Level
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
                                        <TableHead className="w-[80px]">Level #</TableHead>
                                        <TableHead className="w-[60px]">Icon</TableHead>
                                        <TableHead>Name</TableHead>
                                        <TableHead>XP Range</TableHead>
                                        <TableHead className="text-center">Users</TableHead>
                                        <TableHead>Color</TableHead>
                                        <TableHead className="w-[120px]">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {levels.data.map((level) => (
                                        <TableRow key={level.id}>
                                            <TableCell className="font-bold text-lg">
                                                {level.level_number}
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center justify-center">
                                                    {level.icon ? (
                                                        <span className="text-xl" style={{ color: level.color }}>
                                                            {level.icon}
                                                        </span>
                                                    ) : (
                                                        <div 
                                                            className="w-6 h-6 rounded"
                                                            style={{ backgroundColor: level.color }}
                                                        />
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div>
                                                    <div className="font-medium" style={{ color: level.color }}>
                                                        {level.name}
                                                    </div>
                                                    {level.description && (
                                                        <div className="text-sm text-muted-foreground">
                                                            {level.description}
                                                        </div>
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="space-y-1">
                                                    <div className="text-sm">
                                                        <span className="font-mono">{level.min_xp.toLocaleString()}</span>
                                                        <span className="text-muted-foreground"> - </span>
                                                        <span className="font-mono">{level.max_xp.toLocaleString()}</span>
                                                        <span className="text-muted-foreground"> XP</span>
                                                    </div>
                                                    <div className="text-xs text-muted-foreground">
                                                        Range: {(level.max_xp - level.min_xp + 1).toLocaleString()} XP
                                                    </div>
                                                </div>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex items-center justify-center gap-1">
                                                    <Users className="h-4 w-4" />
                                                    <span>{level.users_count}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <div 
                                                        className="w-4 h-4 rounded-full border"
                                                        style={{ backgroundColor: level.color }}
                                                    />
                                                    <span className="font-mono text-sm">{level.color}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Link href={`/dashboard/levels/${level.id}`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Eye className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Link href={`/dashboard/levels/${level.id}/edit`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Edit className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Button 
                                                        variant="ghost" 
                                                        size="sm"
                                                        onClick={() => handleDelete(level)}
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

                        {levels.last_page > 1 && (
                            <div className="flex items-center justify-between px-2 mt-4">
                                <div className="text-sm text-muted-foreground">
                                    Page {levels.current_page} of {levels.last_page}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}