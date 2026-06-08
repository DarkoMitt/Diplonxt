@props(['status'])
@php $value = $status instanceof \BackedEnum ? $status->value : $status; $label = str($value)->replace('_',' ')->title(); $class = match($value) { 'approved','completed','pass','topic_approved','defense_approved' => 'bg-emerald-100 text-emerald-700', 'rejected','fail' => 'bg-rose-100 text-rose-700', 'revision_required' => 'bg-amber-100 text-amber-700', 'archived' => 'bg-slate-200 text-slate-600', default => 'bg-blue-100 text-blue-700' }; @endphp
<span {{ $attributes->class("inline-flex rounded-full px-3 py-1 text-xs font-bold $class") }}>{{ $label }}</span>
