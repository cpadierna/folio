export function useStatusBadge() {
    const statusLabel = {
        want_to_read: 'Want to Read',
        reading: 'Reading',
        read: 'Read',
    }
    const statusClass = {
        want_to_read: 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-300 text-slate-700',
        reading: 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700',
        read: 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700',
    }
    return { statusLabel, statusClass }
}
