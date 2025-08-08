import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import { ArrowLeft, Save } from 'lucide-react';
import { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Languages',
        href: '/dashboard/languages',
    },
    {
        title: 'Create',
        href: '/dashboard/languages/create',
    },
];

export default function CreateLanguage() {
    const { data, setData, post, processing, errors } = useForm({
        language: '',
        code: '',
        is_active: true,
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/dashboard/languages');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add Language" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card className="max-w-2xl mx-auto w-full">
                    <CardHeader>
                        <div className="flex items-center gap-4">
                            <Link href="/dashboard/languages">
                                <Button variant="ghost" size="sm">
                                    <ArrowLeft className="h-4 w-4" />
                                </Button>
                            </Link>
                            <div>
                                <CardTitle>Add Language</CardTitle>
                                <CardDescription>
                                    Add a new language to the system
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="language">Language Name *</Label>
                                <Input
                                    id="language"
                                    value={data.language}
                                    onChange={(e) => setData('language', e.target.value)}
                                    placeholder="e.g., English, Spanish, French"
                                    className={errors.language ? 'border-red-500' : ''}
                                />
                                {errors.language && <p className="text-sm text-red-500">{errors.language}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="code">Language Code *</Label>
                                <Input
                                    id="code"
                                    value={data.code}
                                    onChange={(e) => setData('code', e.target.value.toLowerCase())}
                                    placeholder="e.g., en, es, fr, de"
                                    maxLength={10}
                                    className={errors.code ? 'border-red-500' : ''}
                                />
                                {errors.code && <p className="text-sm text-red-500">{errors.code}</p>}
                                <p className="text-xs text-muted-foreground">
                                    Use ISO language codes (e.g., en, es, fr) or locale codes (e.g., en-us, pt-br)
                                </p>
                            </div>

                            <div className="flex items-center space-x-2">
                                <Checkbox
                                    id="is_active"
                                    checked={data.is_active}
                                    onCheckedChange={(checked) => setData('is_active', !!checked)}
                                />
                                <Label htmlFor="is_active">Active Language</Label>
                                <p className="text-xs text-muted-foreground ml-2">
                                    (Active languages are available for user selection)
                                </p>
                            </div>

                            {/* Preview */}
                            <div className="border rounded-lg p-4 bg-muted/50">
                                <Label className="text-sm font-medium">Preview:</Label>
                                <div className="flex items-center gap-3 mt-2">
                                    <div className="font-medium">
                                        {data.language || 'Language Name'}
                                    </div>
                                    <code className="bg-background px-2 py-1 rounded text-sm">
                                        {data.code || 'code'}
                                    </code>
                                    {data.is_active ? (
                                        <Badge variant="default" className="bg-green-500 hover:bg-green-600 text-xs">
                                            Active
                                        </Badge>
                                    ) : (
                                        <Badge variant="secondary" className="text-xs">
                                            Inactive
                                        </Badge>
                                    )}
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Link href="/dashboard/languages">
                                    <Button variant="outline" type="button">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" disabled={processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Adding...' : 'Add Language'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}