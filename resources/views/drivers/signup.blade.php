@php
    $stepKeys = array_keys($steps);
    $currentIndex = array_search($step, $stepKeys, true);
    $currentStep = $steps[$step];
    $previousStep = $currentIndex > 0 ? $stepKeys[$currentIndex - 1] : null;
    $nextLabel = match ($step) {
        'account' => auth()->check() ? 'متابعة' : 'إنشاء الحساب',
        'review' => 'إرسال الطلب',
        default => 'متابعة',
    };
    $fieldValue = fn (string $name): mixed => old($name, data_get($draft, 'fields.'.$name, ''));
    $photoPath = fn (string $name): ?string => data_get($draft, 'photos.'.$name);

    $documentUploads = [
        'national_id_front_photo' => 'وجه بطاقة الرقم القومي',
        'national_id_back_photo' => 'ظهر بطاقة الرقم القومي',
        'selfie_photo' => 'الصورة الشخصية',
        'driver_license_photo' => 'رخصة القيادة',
        'vehicle_license_photo' => 'رخصة المركبة',
        'criminal_record_certificate_photo' => 'صحيفة الحالة الجنائية',
        'drug_test_photo' => 'صورة تحليل المخدرات',
    ];

    $vehicleUploads = [
        'vehicle_front_photo' => 'صورة المركبة من الأمام',
        'vehicle_side_photo' => 'صورة المركبة من الجانب',
        'vehicle_back_photo' => 'صورة المركبة من الخلف',
        'delivery_box_photo' => 'صورة صندوق التوصيل',
    ];

    $summaryGroups = [
        'الهوية القانونية' => [
            'legal_name' => 'الاسم القانوني الكامل',
            'date_of_birth' => 'تاريخ الميلاد',
            'current_address' => 'العنوان الحالي',
        ],
        'التواصل والطوارئ' => [
            'phone_number' => 'رقم الهاتف',
            'backup_phone_number' => 'رقم الهاتف الاحتياطي',
            'emergency_contact_name' => 'جهة اتصال الطوارئ',
            'emergency_contact_relationship' => 'صلة القرابة',
            'emergency_contact_phone' => 'هاتف الطوارئ',
        ],
        'الموتوسيكل' => [
            'plate_number' => 'رقم اللوحة',
            'vehicle_owner_name' => 'مالك المركبة',
            'chassis_number' => 'رقم الشاسيه',
            'motor_number' => 'رقم الموتور',
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>تسجيل السائق | {{ config('app.name', 'طياران') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-zinc-950 font-sans text-zinc-100 antialiased">
        <main class="min-h-screen">
            <section class="min-h-screen border-b border-white/10 bg-[radial-gradient(circle_at_top_left,_rgba(20,184,166,0.20),_transparent_34rem),linear-gradient(135deg,_#09090b_0%,_#111827_46%,_#172554_100%)]">
                <div class="mx-auto grid max-w-7xl gap-8 px-5 py-8 sm:px-6 lg:grid-cols-[0.78fr_1.22fr] lg:px-8 lg:py-10">
                    <aside class="flex flex-col justify-between gap-8">
                        <a href="{{ route('drivers.signup.create') }}" class="inline-flex items-center gap-3 text-white">
                            <span class="flex size-11 items-center justify-center rounded-2xl bg-teal-400 text-lg font-black text-zinc-950 shadow-lg shadow-teal-950/40">ط</span>
                            <span>
                                <span class="block text-xl font-extrabold tracking-wide">طياران</span>
                                <span class="block text-sm text-teal-100/80">توصيل بالموتوسيكلات في مصر</span>
                            </span>
                        </a>

                        <div class="max-w-xl">
                            <p class="mb-4 inline-flex rounded-full border border-teal-300/30 bg-teal-300/10 px-3 py-1 text-sm font-medium text-teal-100">
                                طلب تسجيل السائق
                            </p>
                            <h1 class="text-4xl font-black leading-tight text-white sm:text-5xl">
                                الخطوة {{ $currentStep['number'] }}: {{ $currentStep['title'] }}
                            </h1>
                            <p class="mt-5 text-lg leading-8 text-zinc-200">
                                {{ $currentStep['subtitle'] }}. الصور اختيارية حاليا أثناء تجهيز مسار التسجيل.
                            </p>
                        </div>

                        <nav class="space-y-3">
                            @foreach ($steps as $key => $signupStep)
                                @php
                                    $isActive = $key === $step;
                                    $isPast = $signupStep['number'] < $currentStep['number'];
                                @endphp

                                <a href="{{ route('drivers.signup.step', $key) }}" class="flex items-center gap-4 rounded-2xl border p-4 transition {{ $isActive ? 'border-teal-300 bg-teal-300/15 text-white' : 'border-white/10 bg-white/5 text-zinc-300 hover:bg-white/10' }}">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl text-sm font-black {{ $isActive || $isPast ? 'bg-teal-300 text-zinc-950' : 'bg-white/10 text-zinc-200' }}">
                                        {{ str_pad((string) $signupStep['number'], 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    <span>
                                        <span class="block text-sm font-extrabold">{{ $signupStep['title'] }}</span>
                                        <span class="mt-0.5 block text-xs opacity-75">{{ $signupStep['subtitle'] }}</span>
                                    </span>
                                </a>
                            @endforeach
                        </nav>
                    </aside>

                    <form method="POST" action="{{ route('drivers.signup.step.store', $step) }}" enctype="multipart/form-data" class="rounded-3xl border border-white/10 bg-white p-4 text-zinc-950 shadow-2xl shadow-black/30 sm:p-6 lg:p-8">
                        @csrf

                        @if ($errors->any())
                            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                                يرجى مراجعة الحقول المحددة والمحاولة مرة أخرى.
                            </div>
                        @endif

                        <div class="flex flex-col gap-2 border-b border-zinc-200 pb-6 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-teal-700">سائق جديد</p>
                                <h2 class="mt-1 text-2xl font-black text-zinc-950">{{ $currentStep['title'] }}</h2>
                            </div>
                            <p class="text-sm font-medium text-zinc-500">الخطوة {{ $currentStep['number'] }} من {{ count($steps) }}</p>
                        </div>

                        <div class="mt-8">
                            @if ($step === 'account')
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">حساب تسجيل الدخول</h3>
                                        <p class="mt-1 text-sm text-zinc-500">أنشئ الحساب الذي ستستخدمه لاحقا لمتابعة حالة طلب السائق.</p>
                                    </div>

                                    @auth
                                        <div class="rounded-2xl border border-teal-200 bg-teal-50 p-5">
                                            <p class="text-sm font-black uppercase tracking-wide text-teal-700">الحساب متصل</p>
                                            <p class="mt-2 text-base font-bold text-zinc-950">{{ auth()->user()->email }}</p>
                                            <p class="mt-2 text-sm leading-6 text-zinc-600">
                                                سيتم ربط طلب السائق بهذا الحساب.
                                            </p>
                                        </div>
                                    @else
                                        <div class="grid gap-5">
                                            <div>
                                                <x-input-label for="email" value="البريد الإلكتروني" />
                                                <x-text-input id="email" name="email" type="email" class="mt-2 block w-full" :value="old('email')" required autofocus autocomplete="email" dir="ltr" />
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                            </div>

                                            <div class="grid gap-5 sm:grid-cols-2">
                                                <div>
                                                    <x-input-label for="password" value="كلمة المرور" />
                                                    <x-text-input id="password" name="password" type="password" class="mt-2 block w-full" required autocomplete="new-password" />
                                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" />
                                                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full" required autocomplete="new-password" />
                                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>
                                    @endauth
                                </section>
                            @elseif ($step === 'identity')
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">الهوية القانونية</h3>
                                        <p class="mt-1 text-sm text-zinc-500">استخدم نفس البيانات الموجودة في بطاقة الرقم القومي.</p>
                                    </div>

                                    <div class="grid gap-5 sm:grid-cols-2">
                                        <div class="sm:col-span-2">
                                            <x-input-label for="legal_name" value="الاسم القانوني الكامل" />
                                            <x-text-input id="legal_name" name="legal_name" type="text" class="mt-2 block w-full" :value="$fieldValue('legal_name')" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->get('legal_name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="date_of_birth" value="تاريخ الميلاد" />
                                            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-2 block w-full" :value="$fieldValue('date_of_birth')" required />
                                            <x-input-error :messages="$errors->get('date_of_birth')" class="mt-2" />
                                        </div>

                                        <div class="sm:col-span-2">
                                            <x-input-label for="current_address" value="العنوان الحالي" />
                                            <textarea id="current_address" name="current_address" rows="4" required class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $fieldValue('current_address') }}</textarea>
                                            <x-input-error :messages="$errors->get('current_address')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>
                            @elseif ($step === 'contact')
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">التواصل والطوارئ</h3>
                                        <p class="mt-1 text-sm text-zinc-500">أضف رقما احتياطيا وجهة اتصال للطوارئ من أجل إجراءات السلامة.</p>
                                    </div>

                                    <div class="grid gap-5 sm:grid-cols-2">
                                        <div>
                                            <x-input-label for="phone_number" value="رقم الهاتف" />
                                            <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-2 block w-full" :value="$fieldValue('phone_number')" required autocomplete="tel" placeholder="+20 10 0000 0000" dir="ltr" />
                                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="backup_phone_number" value="رقم الهاتف الاحتياطي" />
                                            <x-text-input id="backup_phone_number" name="backup_phone_number" type="tel" class="mt-2 block w-full" :value="$fieldValue('backup_phone_number')" required placeholder="+20 11 0000 0000" dir="ltr" />
                                            <x-input-error :messages="$errors->get('backup_phone_number')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="emergency_contact_name" value="اسم جهة اتصال الطوارئ" />
                                            <x-text-input id="emergency_contact_name" name="emergency_contact_name" type="text" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_name')" required />
                                            <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="emergency_contact_relationship" value="صلة القرابة" />
                                            <x-text-input id="emergency_contact_relationship" name="emergency_contact_relationship" type="text" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_relationship')" required placeholder="أخ، زوجة، والد..." />
                                            <x-input-error :messages="$errors->get('emergency_contact_relationship')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="emergency_contact_phone" value="هاتف جهة اتصال الطوارئ" />
                                            <x-text-input id="emergency_contact_phone" name="emergency_contact_phone" type="tel" class="mt-2 block w-full" :value="$fieldValue('emergency_contact_phone')" required placeholder="+20 12 0000 0000" dir="ltr" />
                                            <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                                        </div>
                                    </div>
                                </section>
                            @elseif ($step === 'documents')
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">المستندات وفحوصات السلامة</h3>
                                        <p class="mt-1 text-sm text-zinc-500">كل الصور اختيارية حاليا. أضف المتاح لديك ثم تابع.</p>
                                    </div>

                                    <div class="grid gap-4 sm:grid-cols-2">
                                        @foreach ($documentUploads as $name => $label)
                                            <label for="{{ $name }}" class="group block rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-4 transition hover:border-teal-500 hover:bg-teal-50">
                                                <span class="block text-sm font-bold text-zinc-800">{{ $label }}</span>
                                                <span class="mt-1 block text-xs text-zinc-500">
                                                    {{ $photoPath($name) ? 'تم الرفع: '.basename($photoPath($name)) : 'JPG أو PNG أو WebP' }}
                                                </span>
                                                <input id="{{ $name }}" name="{{ $name }}" type="file" accept="image/png,image/jpeg,image/webp" class="mt-4 block w-full text-xs text-zinc-600 file:mr-3 file:rounded-full file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:text-xs file:font-bold file:text-white group-hover:file:bg-teal-700" />
                                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                                            </label>
                                        @endforeach
                                    </div>
                                </section>
                            @elseif ($step === 'vehicle')
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">بيانات الموتوسيكل</h3>
                                        <p class="mt-1 text-sm text-zinc-500">أدخل بيانات الملكية والتسجيل، ثم أضف صور المركبة الاختيارية.</p>
                                    </div>

                                    <div class="grid gap-5 sm:grid-cols-2">
                                        <div>
                                            <x-input-label for="plate_number" value="رقم اللوحة" />
                                            <x-text-input id="plate_number" name="plate_number" type="text" class="mt-2 block w-full uppercase" :value="$fieldValue('plate_number')" required dir="ltr" />
                                            <x-input-error :messages="$errors->get('plate_number')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="vehicle_owner_name" value="اسم مالك المركبة" />
                                            <x-text-input id="vehicle_owner_name" name="vehicle_owner_name" type="text" class="mt-2 block w-full" :value="$fieldValue('vehicle_owner_name')" required />
                                            <x-input-error :messages="$errors->get('vehicle_owner_name')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="chassis_number" value="رقم الشاسيه" />
                                            <x-text-input id="chassis_number" name="chassis_number" type="text" class="mt-2 block w-full uppercase" :value="$fieldValue('chassis_number')" required dir="ltr" />
                                            <x-input-error :messages="$errors->get('chassis_number')" class="mt-2" />
                                        </div>

                                        <div>
                                            <x-input-label for="motor_number" value="رقم الموتور" />
                                            <x-text-input id="motor_number" name="motor_number" type="text" class="mt-2 block w-full uppercase" :value="$fieldValue('motor_number')" required dir="ltr" />
                                            <x-input-error :messages="$errors->get('motor_number')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                        @foreach ($vehicleUploads as $name => $label)
                                            <label for="{{ $name }}" class="group block rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-4 transition hover:border-teal-500 hover:bg-teal-50">
                                                <span class="block text-sm font-bold text-zinc-800">{{ $label }}</span>
                                                <span class="mt-1 block text-xs text-zinc-500">
                                                    {{ $photoPath($name) ? 'تم الرفع: '.basename($photoPath($name)) : 'صورة اختيارية' }}
                                                </span>
                                                <input id="{{ $name }}" name="{{ $name }}" type="file" accept="image/png,image/jpeg,image/webp" class="mt-4 block w-full text-xs text-zinc-600 file:mr-3 file:rounded-full file:border-0 file:bg-zinc-900 file:px-3 file:py-2 file:text-xs file:font-bold file:text-white group-hover:file:bg-teal-700" />
                                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                                            </label>
                                        @endforeach
                                    </div>
                                </section>
                            @else
                                <section>
                                    <div class="mb-5">
                                        <h3 class="text-lg font-extrabold text-zinc-950">المراجعة والإرسال</h3>
                                        <p class="mt-1 text-sm text-zinc-500">راجع البيانات الأساسية قبل إرسال الطلب لمراجعة الإدارة.</p>
                                    </div>

                                    <div class="space-y-5">
                                        @foreach ($summaryGroups as $title => $fields)
                                            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                                <h4 class="text-sm font-black uppercase tracking-wide text-teal-700">{{ $title }}</h4>
                                                <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                                                    @foreach ($fields as $field => $label)
                                                        <div>
                                                            <dt class="text-xs font-bold uppercase tracking-wide text-zinc-500">{{ $label }}</dt>
                                                            <dd class="mt-1 break-words text-sm font-semibold text-zinc-950">{{ data_get($draft, 'fields.'.$field, 'لم تتم الإضافة') }}</dd>
                                                        </div>
                                                    @endforeach
                                                </dl>
                                            </div>
                                        @endforeach

                                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-5">
                                            <h4 class="text-sm font-black uppercase tracking-wide text-teal-700">الصور المرفوعة</h4>
                                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                                @foreach ([...$documentUploads, ...$vehicleUploads] as $name => $label)
                                                    <div class="flex items-center justify-between gap-3 rounded-xl bg-white px-4 py-3">
                                                        <span class="text-sm font-semibold text-zinc-800">{{ $label }}</span>
                                                        <span class="shrink-0 rounded-full px-3 py-1 text-xs font-black {{ $photoPath($name) ? 'bg-teal-100 text-teal-800' : 'bg-zinc-100 text-zinc-500' }}">
                                                            {{ $photoPath($name) ? 'تمت الإضافة' : 'اختياري' }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="rounded-2xl bg-zinc-950 p-5 text-white">
                                            <div class="space-y-4">
                                                <label for="consented_to_background_check" class="flex gap-3">
                                                    <input id="consented_to_background_check" name="consented_to_background_check" type="checkbox" value="1" required @checked(old('consented_to_background_check')) class="mt-1 rounded border-zinc-500 bg-zinc-900 text-teal-400 focus:ring-teal-400" />
                                                    <span>
                                                        <span class="block text-sm font-bold">أوافق على التحقق من الهوية، صحيفة الحالة الجنائية، الرخصة، وتحليل المخدرات.</span>
                                                        <x-input-error :messages="$errors->get('consented_to_background_check')" class="mt-2" />
                                                    </span>
                                                </label>

                                                <label for="accepted_terms" class="flex gap-3">
                                                    <input id="accepted_terms" name="accepted_terms" type="checkbox" value="1" required @checked(old('accepted_terms')) class="mt-1 rounded border-zinc-500 bg-zinc-900 text-teal-400 focus:ring-teal-400" />
                                                    <span>
                                                        <span class="block text-sm font-bold">أؤكد أن البيانات المقدمة صحيحة.</span>
                                                        <x-input-error :messages="$errors->get('accepted_terms')" class="mt-2" />
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            @endif
                        </div>

                        <div class="mt-8 flex flex-col-reverse gap-3 border-t border-zinc-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            @if ($previousStep)
                                <a href="{{ route('drivers.signup.step', $previousStep) }}" class="inline-flex justify-center rounded-xl px-5 py-3 text-sm font-bold text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950">
                                    رجوع
                                </a>
                            @else
                                <a href="/" class="inline-flex justify-center rounded-xl px-5 py-3 text-sm font-bold text-zinc-600 transition hover:bg-zinc-100 hover:text-zinc-950">
                                    رجوع
                                </a>
                            @endif

                            <button type="submit" class="inline-flex justify-center rounded-xl bg-teal-500 px-6 py-3 text-sm font-black text-zinc-950 shadow-lg shadow-teal-900/20 transition hover:bg-teal-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                {{ $nextLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
