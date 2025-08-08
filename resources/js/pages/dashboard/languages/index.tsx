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
import { Plus, Edit, Trash2, Eye, Users, ToggleLeft, ToggleRight } from 'lucide-react';

interface Language {
    id: number;
    language: string;
    code: string;
    emoji: string;
    is_active: boolean;
    users_count: number;
    created_at: string;
}

interface LanguagesProps {
    languages: {
        data: Language[];
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
        title: 'Languages',
        href: '/dashboard/languages',
    },
];

export default function Languages({ languages }: LanguagesProps) {
    const handleDelete = (language: Language) => {
        if (language.users_count > 0) {
            alert('Cannot delete language that is assigned to users.');
            return;
        }
        
        if (confirm(`Are you sure you want to delete "${language.language}"?`)) {
            router.delete(`/dashboard/languages/${language.id}`);
        }
    };

    const handleToggle = (language: Language) => {
        router.patch(`/dashboard/languages/${language.id}/toggle`);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Languages Management" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between">
                            <div>
                                <CardTitle>Languages</CardTitle>
                                <CardDescription>
                                    Manage system languages and localization
                                </CardDescription>
                            </div>
                            <div className="flex items-center gap-2">
                                <Badge variant="secondary" className="text-sm">
                                    Total: {languages.total}
                                </Badge>
                                <Badge variant="outline" className="text-sm">
                                    Active: {languages.data.filter(lang => lang.is_active).length}
                                </Badge>
                                <Link href="/dashboard/languages/create">
                                    <Button>
                                        <Plus className="h-4 w-4 mr-2" />
                                        Add Language
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
                                        <TableHead>Language</TableHead>
                                        <TableHead>Code</TableHead>
                                        <TableHead className="text-center">Status</TableHead>
                                        <TableHead className="text-center">Users</TableHead>
                                        <TableHead>Created</TableHead>
                                        <TableHead className="w-[180px]">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {languages.data.map((language) => (
                                        <TableRow key={language.id}>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <div className="flex items-center gap-2">
                                                        <span className="text-xl">{language.emoji}</span>
                                                        <div className="font-medium">
                                                            {language.language}
                                                        </div>
                                                    </div>
                                                    {language.code === 'en' && (
                                                        <Badge variant="outline" className="text-xs">
                                                            Default
                                                        </Badge>
                                                    )}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <code className="bg-muted px-2 py-1 rounded text-sm">
                                                    {language.code}
                                                </code>
                                            </TableCell>
                                            <TableCell className="text-center">
                                                {language.is_active ? (
                                                    <Badge variant="default" className="bg-green-500 hover:bg-green-600">
                                                        Active
                                                    </Badge>
                                                ) : (
                                                    <Badge variant="secondary">
                                                        Inactive
                                                    </Badge>
                                                )}
                                            </TableCell>
                                            <TableCell className="text-center">
                                                <div className="flex items-center justify-center gap-1">
                                                    <Users className="h-4 w-4" />
                                                    <span>{language.users_count}</span>
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="text-sm text-muted-foreground">
                                                    {new Date(language.created_at).toLocaleDateString()}
                                                </div>
                                            </TableCell>
                                            <TableCell>
                                                <div className="flex items-center gap-2">
                                                    <Button
                                                        variant="ghost"
                                                        size="sm"
                                                        onClick={() => handleToggle(language)}
                                                        className={language.is_active ? 'text-orange-600 hover:text-orange-700' : 'text-green-600 hover:text-green-700'}
                                                        title={language.is_active ? 'Deactivate' : 'Activate'}
                                                    >
                                                        {language.is_active ? (
                                                            <ToggleRight className="h-4 w-4" />
                                                        ) : (
                                                            <ToggleLeft className="h-4 w-4" />
                                                        )}
                                                    </Button>
                                                    <Link href={`/dashboard/languages/${language.id}`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Eye className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Link href={`/dashboard/languages/${language.id}/edit`}>
                                                        <Button variant="ghost" size="sm">
                                                            <Edit className="h-4 w-4" />
                                                        </Button>
                                                    </Link>
                                                    <Button 
                                                        variant="ghost" 
                                                        size="sm"
                                                        onClick={() => handleDelete(language)}
                                                        disabled={language.users_count > 0}
                                                        className="text-red-600 hover:text-red-700 hover:bg-red-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                                        title={language.users_count > 0 ? 'Cannot delete: Language is in use' : 'Delete language'}
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

                        {languages.last_page > 1 && (
                            <div className="flex items-center justify-between px-2 mt-4">
                                <div className="text-sm text-muted-foreground">
                                    Page {languages.current_page} of {languages.last_page}
                                </div>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}