<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.shared/title-meta', ['title' => "Register"])

    @include('layouts.shared/head-css')
</head>

<body>

<div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">

    <div class="h-screen w-screen flex justify-center items-center">
        <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
            <div class="card overflow-hidden sm:rounded-md rounded-none">
                <div class="p-6">
                    <a href="{{ route('home') }}" class="block mb-8">
                        <img class="h-6 block dark:hidden" src="/images/logo-dark.png" alt="">
                        <img class="h-6 hidden dark:block" src="/images/logo-light.png" alt="">
                    </a>
                    <form  method="POST" action="{{ route('users.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                   for="LoggingName">Full Name</label>
                            <input id="LoggingName" name="name" class="form-input" type="text" placeholder="Enter your Name" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                   for="LoggingEmailAddress">Email Address</label>
                            <input id="LoggingEmailAddress" name="email" class="form-input" type="email" placeholder="Enter your email" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                   for="loggingPassword">Password</label>
                            <input id="loggingPassword" name="password" class="form-input" type="password"
                                   placeholder="Enter your password">
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                   for="confirmedPassword">Confirmed Password</label>
                            <input id="confirmedPassword" name="confirmed_password" class="form-input" type="password"
                                   placeholder="Re-enter your password">
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="check_box" class="form-checkbox rounded" id="checkbox-signup">
                                <label class="ms-2 text-slate-900 dark:text-slate-200" for="checkbox-signup">I accept <a
                                        href="#" class="text-gray-400 underline">Terms and Conditions</a></label>
                            </div>
                            @error('check_box')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-center mb-6">
                            <button type="submit" class="btn w-full text-white bg-primary"> Register</button>
                        </div>
                    </form>
                    <div class="flex items-center my-6">
                        <div class="flex-auto mt-px border-t border-dashed border-gray-200 dark:border-slate-700"></div>
                        <div class="mx-4 text-secondary">Or</div>
                        <div class="flex-auto mt-px border-t border-dashed border-gray-200 dark:border-slate-700"></div>
                    </div>

                    <div class="flex gap-4 justify-center mb-6">
                        <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_github_line text-info text-xl"></i>
                                    <span class="lg:block hidden">Github</span>
                                </span>
                        </a>
                        <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_google_line text-danger text-xl"></i>
                                    <span class="lg:block hidden">Google</span>
                                </span>
                        </a>
                        <a href="javascript:void(0)" class="btn border-light text-gray-400 dark:border-slate-700">
                                <span class="flex justify-center items-center gap-2">
                                    <i class="mgc_facebook_line text-primary text-xl"></i>
                                    <span class="lg:block hidden">Facebook</span>
                                </span>
                        </a>
                    </div>

                    <p class="text-gray-500 dark:text-gray-400 text-center">Already have account ?<a
                            href="{{ route('second', ['auth', 'login']) }}" class="text-primary ms-1"><b>Log In</b></a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>
