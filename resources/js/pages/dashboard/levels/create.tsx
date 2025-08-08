import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Save } from 'lucide-react';
import { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Levels',
        href: '/dashboard/levels',
    },
    {
        title: 'Create',
        href: '/dashboard/levels/create',
    },
];

export default function CreateLevel() {
    const { data, setData, post, processing, errors } = useForm({
        level_number: 1,
        name: '',
        description: '',
        min_xp: 0,
        max_xp: 100,
        color: '#10B981',
        icon: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/dashboard/levels');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Level" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card className="max-w-2xl mx-auto w-full">
                    <CardHeader>
                        <div className="flex items-center gap-4">
                            <Link href="/dashboard/levels">
                                <Button variant="ghost" size="sm">
                                    <ArrowLeft className="h-4 w-4" />
                                </Button>
                            </Link>
                            <div>
                                <CardTitle>Create Level</CardTitle>
                                <CardDescription>
                                    Add a new level to the progression system
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="level_number">Level Number *</Label>
                                    <Input
                                        id="level_number"
                                        type="number"
                                        value={data.level_number}
                                        onChange={(e) => setData('level_number', parseInt(e.target.value) || 1)}
                                        min="1"
                                        className={errors.level_number ? 'border-red-500' : ''}
                                    />
                                    {errors.level_number && <p className="text-sm text-red-500">{errors.level_number}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="name">Name *</Label>
                                    <Input
                                        id="name"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                        placeholder="e.g., Beginner, Expert, Master"
                                        className={errors.name ? 'border-red-500' : ''}
                                    />
                                    {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Description</Label>
                                <textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Optional description for this level..."
                                    rows={3}
                                    className={`flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ${errors.description ? 'border-red-500' : ''}`}
                                />
                                {errors.description && <p className="text-sm text-red-500">{errors.description}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="min_xp">Minimum XP *</Label>
                                    <Input
                                        id="min_xp"
                                        type="number"
                                        value={data.min_xp}
                                        onChange={(e) => setData('min_xp', parseInt(e.target.value) || 0)}
                                        min="0"
                                        className={errors.min_xp ? 'border-red-500' : ''}
                                    />
                                    {errors.min_xp && <p className="text-sm text-red-500">{errors.min_xp}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="max_xp">Maximum XP *</Label>
                                    <Input
                                        id="max_xp"
                                        type="number"
                                        value={data.max_xp}
                                        onChange={(e) => setData('max_xp', parseInt(e.target.value) || 100)}
                                        min={data.min_xp + 1}
                                        className={errors.max_xp ? 'border-red-500' : ''}
                                    />
                                    {errors.max_xp && <p className="text-sm text-red-500">{errors.max_xp}</p>}
                                    <p className="text-xs text-muted-foreground">
                                        Range: {(data.max_xp - data.min_xp + 1).toLocaleString()} XP
                                    </p>
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="color">Color *</Label>
                                    <div className="flex items-center gap-2">
                                        <Input
                                            id="color"
                                            type="color"
                                            value={data.color}
                                            onChange={(e) => setData('color', e.target.value)}
                                            className={`w-20 h-10 ${errors.color ? 'border-red-500' : ''}`}
                                        />
                                        <Input
                                            value={data.color}
                                            onChange={(e) => setData('color', e.target.value)}
                                            placeholder="#10B981"
                                            className={errors.color ? 'border-red-500' : ''}
                                        />
                                    </div>
                                    {errors.color && <p className="text-sm text-red-500">{errors.color}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="icon">Icon</Label>
                                    <Input
                                        id="icon"
                                        value={data.icon}
                                        onChange={(e) => setData('icon', e.target.value)}
                                        placeholder="🏆 (optional emoji)"
                                        maxLength={10}
                                        className={errors.icon ? 'border-red-500' : ''}
                                    />
                                    {errors.icon && <p className="text-sm text-red-500">{errors.icon}</p>}
                                </div>
                            </div>

                            {/* Preview */}
                            <div className="border rounded-lg p-4 bg-muted/50">
                                <Label className="text-sm font-medium">Preview:</Label>
                                <div className="flex items-center gap-3 mt-2">
                                    <div className="font-bold text-xl">
                                        {data.level_number}
                                    </div>
                                    {data.icon && (
                                        <span className="text-xl" style={{ color: data.color }}>
                                            {data.icon}
                                        </span>
                                    )}
                                    <div>
                                        <div className="font-medium" style={{ color: data.color }}>
                                            {data.name || 'Level Name'}
                                        </div>
                                        <div className="text-sm text-muted-foreground">
                                            {data.min_xp.toLocaleString()} - {data.max_xp.toLocaleString()} XP
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Link href="/dashboard/levels">
                                    <Button variant="outline" type="button">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" disabled={processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Creating...' : 'Create Level'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}