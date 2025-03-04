<script setup lang="ts">
    import AppLayout from '@/layouts/AppLayout.vue';
    import { TransitionRoot } from '@headlessui/vue';
    import { Head, useForm, Link } from '@inertiajs/vue3';
    import { computed, ref } from 'vue';
    import { Button } from '@/components/ui/button';
    import InputError from '@/components/InputError.vue';
    import { TextArea } from '@/components/ui/text-area';
    import { Select } from '@/components/ui/select';
    import { Input } from '@/components/ui/input';
    import { Label } from '@/components/ui/label';
    import type { Customer, Category } from '@/types';
    import DeleteWarning from '@/components/DeleteWarning.vue';

    import {
        Dialog,
        DialogClose,
        DialogContent,
        DialogDescription,
        DialogFooter,
        DialogHeader,
        DialogTitle,
        DialogTrigger,
    } from '@/components/ui/dialog';

    interface Props {
        customers: Array<Customer>,
        categories: Array<Category>,
        contacts_count: number,
    }

    const props = defineProps<Props>()

    const editingCustomer = ref<Customer|boolean>(false);
    const deletionWarningModal = ref(null);

    const form = useForm({
        name: '',
        description: '',
        reference: '',
        category_id: null,
        start_date: ''
    })

    const categoryOptions = computed(() => {
        return props.categories.map((category: Category) => {
            return {
                value: category.id,
                label: category.name
            }
        })
    })

    const submitChanges = () => {
        if(editingCustomer.value === true) {
            form.post(route('customers.store'), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    closeModal();
                }
            })
        } else {
            form.patch(route('customers.update', {customer: editingCustomer.value.id}), {
                preserveScroll: true,
                preserveState: true, 
                onSuccess: () => {
                    closeModal();
                }
            })
        }
    }

    const editCustomer = (customer: Customer) => {
        editingCustomer.value = customer;

        form.name = customer.name;
        form.reference = customer.reference;
        form.category_id = customer.category_id
        form.start_date = customer.start_date
        form.description = customer.description
    }

    const deleteCustomer = (customer: Customer) => {
        form.delete(route('customers.destroy', {customer: customer.id}), {
            preserveScroll: true,
            preserveState: false,
            onSuccess: () => {
                deletionWarningModal.value?.close();
            }
        })
    }

    const initiateDeletion = (customer: Customer) => {
        deletionWarningModal.value.confirm(customer);
    }

    const closeModal = () => {
        editingCustomer.value = false;
        form.name = '';
        form.category_id = null;
        form.reference = '';
        form.start_date = '';
        form.description = ''
    }

    const searchForm = useForm({
        query: '',
        category_id: null,
    })

    const performSearch = () => {
        searchForm.get(route('customers.index'), {
            preserveState: true,
        })
    }

    const clearSearch = () => {
        searchForm.query = '';
        searchForm.category_id = null;
        performSearch()
    }
</script>
<template>
    <AppLayout>
        <Head title="Manage Customers"/>
        <div class="px-4 py-6">
            <div class="mb-8 space-y-0.5 flex justify-between">
                <h2 class="text-xl font-semibold tracking-tight">Customers</h2>
                <Button @click="() => editingCustomer = true">Create</Button>
            </div>

            <form class="flex justify-between items-center gap-2 mb-6" @submit.prevent="performSearch">
                <Label for="search">Search</Label>
                <Input id="search" type="search" name="search"  v-model="searchForm.query" />
                <Label for="category_id">Category</Label>
                <Select v-model="searchForm.category_id" :options="categoryOptions" for="category_id"/>
                <Button variant="outline" type="button" @click="clearSearch">Clear</Button>
                <Button type="submit">Search</Button>
            </form>

            <div class="flex flex-col space-y-8 md:space-y-0 lg:flex-row lg:space-x-12 lg:space-y-0">
                <div class="flex w-full max-w-full">
                    <section class="space-y-12">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Name
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Reference
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right">
                                            No. of Contacts
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="customer in props.customers" :key="customer.id" class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ customer.name }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ customer.reference }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{ customer.contacts_count || 0 }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <button class="text-blue-500" @click="editCustomer(customer)">Edit</button>
                                                <span>|</span>
                                                <button class="text-red-500" @click.prevent="initiateDeletion(customer)">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        <DeleteWarning ref="deletionWarningModal" @confirmed="deleteCustomer"/>
        <Dialog v-model:open="editingCustomer" class="max-w-4xl">
            <DialogContent>
                <form class="space-y-6" @submit.prevent="submitChanges">
                    <DialogHeader class="space-y-3">
                        <DialogTitle class="flex justify-between mr-5">
                            <div>Customers - Detail</div>
                            <div class="flex space-x-2">
                                <Link as="button" @click="closeModal">Back</Link>
                                <Button type="submit">Save</Button>
                            </div>
                        </DialogTitle>
                    </DialogHeader>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="grid gap-2 mb-2">
                                <Label for="name">Name</Label>
                                <Input id="name" type="text" name="name" ref="nameInput" v-model="form.name" placeholder="E.g. Acme Ltd" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="grid gap-2 mb-2">
                                <Label for="reference">Reference</Label>
                                <Input id="reference" type="text" name="reference" v-model="form.reference" placeholder="CUST-XX" />
                                <InputError :message="form.errors.reference" />
                            </div>
                            <div class="grid gap-2 mb-2">
                                <Label for="reference">Category</Label>
                                <Select v-model="form.category_id" :options="categoryOptions"/>
                                <InputError :message="form.errors.category_id" />
                            </div>
                        </div>
                        <div>
                            <div class="grid gap-2 mb-2">
                                <Label for="start_date">Name</Label>
                                <Input id="start_date" type="date" name="start_date" v-model="form.start_date" />
                                <InputError :message="form.errors.start_date" />
                            </div>
                            <div class="grid gap-2 mb-2">
                                <Label for="description">Description</Label>
                                <TextArea rows="6" id="description" name="description" v-model="form.description"/>
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>