<script setup>
    const event = defineEmits(['confirmed'])
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
    import { Button } from '@/components/ui/button';
    import { ref } from 'vue';

    const confirmDeletion = () => {
        event('confirmed', startConfirmingDeletion.value)
    };

    defineExpose({
        close: () => startConfirmingDeletion.value=false,
        confirm: (model) => startConfirmingDeletion.value=model,
    })

    const startConfirmingDeletion = ref(false)
</script>
<template>
    <Dialog v-model:open="startConfirmingDeletion">
        <DialogContent>
            <div class="space-y-6">
                <DialogHeader class="space-y-3">
                    <DialogTitle>
                        <slot name="title">Are you sure you want to delete this resource?</slot>
                    </DialogTitle>
                    <DialogDescription>
                        <slot name="description">
                            Once this resource is deleted, all of its resources and data will also be permanently deleted.
                        </slot>
                    </DialogDescription>
                </DialogHeader>

                <DialogFooter class="gap-2">
                    <DialogClose as-child>
                        <Button variant="secondary"> Cancel </Button>
                    </DialogClose>

                    <Button variant="destructive" @click.prevent="confirmDeletion">
                        <div>Delete</div>
                    </Button>
                </DialogFooter>
            </div>
        </DialogContent>
    </Dialog>
</template>