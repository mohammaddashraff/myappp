@extends('riders.marketplace.layout')

@section('title', 'Dealer Inquiry')
@section('active', 'dealers')

@section('content')
    <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm font-bold uppercase text-teal-700">Dealer inquiry</p>
        <h1 class="mt-2 text-3xl font-black text-slate-950">{{ $dealer->name }}</h1>
        <p class="mt-2 text-sm text-slate-500">{{ $motorcycle?->fullName() ?? 'General showroom inquiry' }}</p>
    </section>

    <form method="POST" action="{{ $motorcycle ? route('rider.dealer-motorcycles.inquiries.store', [$dealer, $motorcycle]) : route('rider.dealers.inquiries.store', $dealer) }}" class="mt-5 rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Rider name
                <input type="text" name="rider_name" value="{{ old('rider_name', $rider->full_name) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Phone
                <input type="text" name="phone" value="{{ old('phone', $rider->phone_number) }}" class="rounded-md border-slate-300 text-sm">
            </label>
            <label class="grid gap-2 text-sm font-bold text-slate-700">
                Preferred contact method
                <select name="preferred_contact_method" class="rounded-md border-slate-300 text-sm">
                    <option value="phone" @selected(old('preferred_contact_method') === 'phone')>Phone</option>
                    <option value="whatsapp" @selected(old('preferred_contact_method') === 'whatsapp')>WhatsApp</option>
                    <option value="email" @selected(old('preferred_contact_method') === 'email')>Email</option>
                </select>
            </label>
            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm font-bold text-slate-600">
                Interested motorcycle: {{ $motorcycle?->fullName() ?? 'Not selected' }}
            </div>
            <label class="grid gap-2 text-sm font-bold text-slate-700 md:col-span-2">
                Message
                <textarea name="message" rows="5" class="rounded-md border-slate-300 text-sm" placeholder="Ask about availability, viewing, price, or installment options">{{ old('message') }}</textarea>
            </label>
        </div>
        <button type="submit" class="mt-6 inline-flex justify-center rounded-md bg-slate-950 px-5 py-3 text-sm font-black text-white">Send Inquiry</button>
    </form>
@endsection
