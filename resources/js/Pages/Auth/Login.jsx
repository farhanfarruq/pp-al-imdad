import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center p-4">
            <Head title="Log in" />

            <div className="w-full max-w-4xl mx-auto bg-gray-800 rounded-2xl shadow-2xl flex">
                {/* Kolom Kiri - Branding */}
                <div className="w-1/2 hidden md:flex flex-col items-center justify-center p-12 bg-gray-900 rounded-l-2xl border-r border-gray-700">
                    <Link href="/">
                        <ApplicationLogo className="w-24 h-24 fill-current text-gray-400" />
                    </Link>
                    <h1 className="mt-6 text-3xl font-bold text-center tracking-wide">
                        Sistem Laporan Kegiatan
                    </h1>
                    <p className="mt-2 text-center text-gray-400">
                        Selamat datang kembali! Silakan login untuk melanjutkan.
                    </p>
                </div>

                {/* Kolom Kanan - Form */}
                <div className="w-full md:w-1/2 p-8 sm:p-12">
                    <h2 className="text-2xl font-bold text-center md:text-left mb-6">Login ke Akun Anda</h2>

                    {status && <div className="mb-4 font-medium text-sm text-green-500">{status}</div>}

                    <form onSubmit={submit} className="space-y-6">
                        <div>
                            <InputLabel htmlFor="email" value="Email" className="text-gray-300" />
                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-white"
                                autoComplete="username"
                                isFocused={true}
                                onChange={(e) => setData('email', e.target.value)}
                            />
                            <InputError message={errors.email} className="mt-2" />
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value="Password" className="text-gray-300" />
                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="mt-1 block w-full bg-gray-700 border-gray-600 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-white"
                                autoComplete="current-password"
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>

                        <div className="flex items-center justify-between">
                            <label className="flex items-center">
                                <Checkbox
                                    name="remember"
                                    checked={data.remember}
                                    onChange={(e) => setData('remember', e.target.checked)}
                                    className="rounded bg-gray-700 border-gray-600 text-indigo-500 focus:ring-indigo-600"
                                />
                                <span className="ms-2 text-sm text-gray-400">Ingat saya</span>
                            </label>

                            {canResetPassword && (
                                <Link
                                    href={route('password.request')}
                                    className="underline text-sm text-gray-400 hover:text-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-800"
                                >
                                    Lupa password?
                                </Link>
                            )}
                        </div>

                        <div className="flex items-center justify-end">
                            <PrimaryButton className="w-full flex justify-center py-3" disabled={processing}>
                                Log in
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}