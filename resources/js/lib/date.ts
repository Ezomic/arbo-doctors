export function formatDate(dateStr: string | null | undefined): string {
    if (!dateStr) {
        return '';
    }

    return new Date(dateStr).toLocaleDateString('nl-NL', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
}
