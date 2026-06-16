<?php
// Usage: include with $status variable set
// echo bookingBadge($status);
function bookingBadge(string $status): string {
    $map = [
        'pending'     => ['bg-yellow-100 text-yellow-800', 'Menunggu Konfirmasi'],
        'confirmed'   => ['bg-blue-100 text-blue-800',    'Dikonfirmasi'],
        'in_progress' => ['bg-purple-100 text-purple-800','Sedang Berlangsung'],
        'completed'   => ['bg-green-100 text-green-800',  'Selesai'],
        'cancelled'   => ['bg-red-100 text-red-800',      'Dibatalkan'],
    ];
    [$class, $label] = $map[$status] ?? ['bg-gray-100 text-gray-800', ucfirst($status)];
    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $class\">$label</span>";
}

function paymentBadge(string $status): string {
    $map = [
        'pending'  => ['bg-yellow-100 text-yellow-800', 'Menunggu Verifikasi'],
        'verified' => ['bg-green-100 text-green-800',   'Terverifikasi'],
        'rejected' => ['bg-red-100 text-red-800',       'Ditolak'],
    ];
    [$class, $label] = $map[$status] ?? ['bg-gray-100 text-gray-800', ucfirst($status)];
    return "<span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $class\">$label</span>";
}
