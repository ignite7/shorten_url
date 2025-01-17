<?php

declare(strict_types=1);

use App\Models\User;
use App\Policies\UserPolicy;

describe('view any', function (): void {
    describe('admin', function (): void {
        it('can view any', function (): void {
            $user = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeTrue();
        });
    });

    describe('staff', function (): void {
        it('can view any', function (): void {
            $user = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeTrue();
        });
    });

    describe('regular', function (): void {
        it('cannot view any', function (): void {
            $user = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeFalse();
        });
    });
});

describe('view', function (): void {
    describe('admin', function (): void {
        it('can view itself', function (): void {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $admin))->toBeTrue();
        });

        it('can view staff', function (): void {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $staff))->toBeTrue();
        });

        it('can view regular', function (): void {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function (): void {
        it('can view itself', function (): void {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $staff))->toBeTrue();
        });

        it('can view admin', function (): void {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $admin))->toBeTrue();
        });

        it('can view regular', function (): void {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function (): void {
        it('can view itself', function (): void {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $regular))->toBeTrue();
        });

        it('cannot view admin', function (): void {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $admin))->toBeFalse();
        });

        it('cannot view staff', function (): void {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $staff))->toBeFalse();
        });

        it('cannot view other regular users', function (): void {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('create', function (): void {
    describe('admin', function (): void {
        it('can create', function (): void {
            $user = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeTrue();
        });
    });

    describe('staff', function (): void {
        it('cannot create', function (): void {
            $user = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });

    describe('regular', function (): void {
        it('cannot create', function (): void {
            $user = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });
});

describe('update', function (): void {
    describe('admin', function (): void {
        it('can update itself', function (): void {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $admin))->toBeTrue();
        });

        it('can update staff', function (): void {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $staff))->toBeTrue();
        });

        it('can update regular', function (): void {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $regular))->toBeTrue();
        });

        it('cannot update other admin', function (): void {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $otherAdmin))->toBeFalse();
        });
    });

    describe('staff', function (): void {
        it('can update itself', function (): void {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $staff))->toBeTrue();
        });

        it('cannot update admin', function (): void {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $admin))->toBeFalse();
        });

        it('cannot update other staff', function (): void {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $otherStaff))->toBeFalse();
        });

        it('can update regular', function (): void {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function (): void {
        it('can update itself', function (): void {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $regular))->toBeTrue();
        });

        it('cannot update admin', function (): void {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $admin))->toBeFalse();
        });

        it('cannot update staff', function (): void {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $staff))->toBeFalse();
        });

        it('cannot update other regular users', function (): void {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('delete', function (): void {
    describe('admin', function (): void {
        it('can delete itself', function (): void {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $admin))->toBeTrue();
        });

        it('can delete staff', function (): void {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $staff))->toBeTrue();
        });

        it('can delete regular', function (): void {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $regular))->toBeTrue();
        });

        it('cannot delete themselves', function (): void {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $otherAdmin))->toBeFalse();
        });
    });

    describe('staff', function (): void {
        it('can delete itself', function (): void {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $staff))->toBeTrue();
        });

        it('cannot delete themselves', function (): void {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $otherStaff))->toBeFalse();
        });

        it('cannot delete admin', function (): void {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $admin))->toBeFalse();
        });

        it('can delete regular', function (): void {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function (): void {
        it('can delete itself', function (): void {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $regular))->toBeTrue();
        });

        it('cannot delete admin', function (): void {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $admin))->toBeFalse();
        });

        it('cannot delete staff', function (): void {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $staff))->toBeFalse();
        });

        it('cannot delete themselves', function (): void {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('restore', function (): void {
    describe('admin', function (): void {
        it('cannot restore itself', function (): void {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $admin))->toBeFalse();
        });

        it('can restore staff', function (): void {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $staff))->toBeTrue();
        });

        it('can restore regular', function (): void {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function (): void {
        it('cannot restore itself', function (): void {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $staff))->toBeFalse();
        });

        it('cannot restore other staff', function (): void {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $otherStaff))->toBeFalse();
        });

        it('cannot restore admin', function (): void {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $admin))->toBeFalse();
        });

        it('can restore regular', function (): void {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function (): void {
        it('cannot restore itself', function (): void {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $regular))->toBeFalse();
        });

        it('cannot restore other regular', function (): void {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $otherRegular))->toBeFalse();
        });

        it('cannot restore admin', function (): void {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $admin))->toBeFalse();
        });

        it('cannot restore staff', function (): void {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $staff))->toBeFalse();
        });
    });
});

describe('forceDelete', function (): void {
    describe('admin', function (): void {
        it('cannot force delete itself', function (): void {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $admin))->toBeFalse();
        });

        it('cannot force delete other admin', function (): void {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $otherAdmin))->toBeFalse();
        });

        it('can force delete staff', function (): void {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $staff))->toBeTrue();
        });

        it('can force delete regular', function (): void {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function (): void {
        it('cannot force delete itself', function (): void {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $staff))->toBeFalse();
        });

        it('cannot force delete other staff', function (): void {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $otherStaff))->toBeFalse();
        });

        it('cannot force delete admin', function (): void {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $admin))->toBeFalse();
        });

        it('cannot force delete regular', function (): void {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $regular))->toBeFalse();
        });
    });

    describe('regular', function (): void {
        it('cannot force delete itself', function (): void {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $regular))->toBeFalse();
        });

        it('cannot force delete other regular', function (): void {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $otherRegular))->toBeFalse();
        });

        it('cannot force delete admin', function (): void {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $admin))->toBeFalse();
        });

        it('cannot force delete staff', function (): void {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $staff))->toBeFalse();
        });
    });
});
