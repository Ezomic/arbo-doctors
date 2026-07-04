const dateFormat = new Intl.DateTimeFormat('nl-NL', { day: 'numeric', month: 'short', year: 'numeric' });
const dateTimeFormat = new Intl.DateTimeFormat('nl-NL', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });

export function formatDate(value: string | null | undefined): string {
    if (!value) {
        return '—';
    }

    return dateFormat.format(new Date(value));
}

export function formatDateTime(value: string | null | undefined): string {
    if (!value) {
        return '—';
    }

    return dateTimeFormat.format(new Date(value));
}
