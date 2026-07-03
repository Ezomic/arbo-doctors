export type User = {
    id: string;
    name: string;
    email: string;
    avatar?: string;
    current_role: string;
    tenant_id: string | null;
    accessible_apps: { slug: string; name: string; base_url: string; as: string }[] | null;
    identity_synced_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};
