import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select } from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft, Save } from 'lucide-react';
import { FormEvent } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Achievements',
        href: '/dashboard/achievements',
    },
    {
        title: 'Create',
        href: '/dashboard/achievements/create',
    },
];

export default function CreateAchievement() {
    const { data, setData, post, processing, errors } = useForm({
        key: '',
        name: '',
        description: '',
        icon: '',
        color: '#10B981',
        xp_reward: 0,
        type: 'general' as 'streak' | 'level' | 'general',
        criteria: {},
        is_hidden: false,
        order: 0,
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/dashboard/achievements');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Achievement" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card className="max-w-2xl mx-auto w-full">
                    <CardHeader>
                        <div className="flex items-center gap-4">
                            <Link href="/dashboard/achievements">
                                <Button variant="ghost" size="sm">
                                    <ArrowLeft className="h-4 w-4" />
                                </Button>
                            </Link>
                            <div>
                                <CardTitle>Create Achievement</CardTitle>
                                <CardDescription>
                                    Add a new achievement to the gamification system
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="key">Key *</Label>
                                    <Input
                                        id="key"
                                        value={data.key}
                                        onChange={(e) => setData('key', e.target.value)}
                                        placeholder="unique_achievement_key"
                                        className={errors.key ? 'border-red-500' : ''}
                                    />
                                    {errors.key && <p className="text-sm text-red-500">{errors.key}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="order">Order *</Label>
                                    <Input
                                        id="order"
                                        type="number"
                                        value={data.order}
                                        onChange={(e) => setData('order', parseInt(e.target.value) || 0)}
                                        min="0"
                                        className={errors.order ? 'border-red-500' : ''}
                                    />
                                    {errors.order && <p className="text-sm text-red-500">{errors.order}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="name">Name *</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Achievement Name"
                                    className={errors.name ? 'border-red-500' : ''}
                                />
                                {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">Description *</Label>
                                <textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    placeholder="Achievement description..."
                                    rows={3}
                                    className={`flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 ${errors.description ? 'border-red-500' : ''}`}
                                />
                                {errors.description && <p className="text-sm text-red-500">{errors.description}</p>}
                            </div>

                            <div className="grid grid-cols-3 gap-4">
                                <div className="space-y-2">
                                    <Label htmlFor="icon">Icon</Label>
                                    <Input
                                        id="icon"
                                        value={data.icon}
                                        onChange={(e) => setData('icon', e.target.value)}
                                        placeholder="🏆"
                                        maxLength={10}
                                        className={errors.icon ? 'border-red-500' : ''}
                                    />
                                    {errors.icon && <p className="text-sm text-red-500">{errors.icon}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="color">Color *</Label>
                                    <Input
                                        id="color"
                                        type="color"
                                        value={data.color}
                                        onChange={(e) => setData('color', e.target.value)}
                                        className={`h-10 ${errors.color ? 'border-red-500' : ''}`}
                                    />
                                    {errors.color && <p className="text-sm text-red-500">{errors.color}</p>}
                                </div>
                                <div className="space-y-2">
                                    <Label htmlFor="xp_reward">XP Reward *</Label>
                                    <Input
                                        id="xp_reward"
                                        type="number"
                                        value={data.xp_reward}
                                        onChange={(e) => setData('xp_reward', parseInt(e.target.value) || 0)}
                                        min="0"
                                        className={errors.xp_reward ? 'border-red-500' : ''}
                                    />
                                    {errors.xp_reward && <p className="text-sm text-red-500">{errors.xp_reward}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="type">Type *</Label>
                                <select
                                    id="type"
                                    value={data.type}
                                    onChange={(e) => setData('type', e.target.value as 'streak' | 'level' | 'general')}
                                    className={`flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 ${errors.type ? 'border-red-500' : ''}`}
                                >
                                    <option value="general">General</option>
                                    <option value="streak">Streak</option>
                                    <option value="level">Level</option>
                                </select>
                                {errors.type && <p className="text-sm text-red-500">{errors.type}</p>}
                            </div>

                            <div className="flex items-center space-x-2">
                                <Checkbox
                                    id="is_hidden"
                                    checked={data.is_hidden}
                                    onCheckedChange={(checked) => setData('is_hidden', !!checked)}
                                />
                                <Label htmlFor="is_hidden">Hidden Achievement</Label>
                            </div>

                            <div className="flex justify-end gap-4">
                                <Link href="/dashboard/achievements">
                                    <Button variant="outline" type="button">
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" disabled={processing}>
                                    <Save className="h-4 w-4 mr-2" />
                                    {processing ? 'Creating...' : 'Create Achievement'}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}