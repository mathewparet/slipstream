import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';

export interface Customer {
    id: number,
    name: string,
    reference: string,
    start_date: string,
    description: string,
    category?: Category,
    category_id?: number|null,
    contacts_count?: number,
    contacts?: Array<Contact>
}

export interface Contact {
    id: number,
    first_name: string,
    last_name: string,
    customer_id: number,
    customer?: Customer
}

export interface Category {
    id: number,
    name: string,
    customers?: Array<Customer>,
    customer_count?: number
}

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;
