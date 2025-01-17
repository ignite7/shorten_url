<?php

use App\Models\User;
use App\Policies\UserPolicy;


describe('view any', function () {
    describe('admin', function () {
        it('can view any', function () {
            $user = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeTrue();
        });
    });

    describe('staff', function () {
        it('can view any', function () {
            $user = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeTrue();
        });
    });

    describe('regular', function () {
        it('cannot view any', function () {
            $user = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->viewAny($user))->toBeFalse();
        });
    });
});

describe('view', function () {
    describe('admin', function () {
        it('can view itself', function () {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $admin))->toBeTrue();
        });

        it('can view staff', function () {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $staff))->toBeTrue();
        });

        it('can view regular', function () {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function () {
        it('can view itself', function () {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $staff))->toBeTrue();
        });

        it('can view admin', function () {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $admin))->toBeTrue();
        });

        it('can view regular', function () {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function () {
        it('can view itself', function () {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $regular))->toBeTrue();
        });

        it('cannot view admin', function () {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $admin))->toBeFalse();
        });

        it('cannot view staff', function () {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $staff))->toBeFalse();
        });

        it('cannot view other regular users', function () {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->view($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('create', function () {
    describe('admin', function () {
        it('can create', function () {
            $user = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeTrue();
        });
    });

    describe('staff', function () {
        it('cannot create', function () {
            $user = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });

    describe('regular', function () {
        it('cannot create', function () {
            $user = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->create($user))->toBeFalse();
        });
    });
});

describe('update', function () {
    describe('admin', function () {
        it('can update itself', function () {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $admin))->toBeTrue();
        });

        it('can update staff', function () {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $staff))->toBeTrue();
        });

        it('can update regular', function () {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $regular))->toBeTrue();
        });

        it('cannot update other admin', function () {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($admin, $otherAdmin))->toBeFalse();
        });
    });

    describe('staff', function () {
        it('can update itself', function () {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $staff))->toBeTrue();
        });

        it('cannot update admin', function () {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $admin))->toBeFalse();
        });

        it('cannot update other staff', function () {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $otherStaff))->toBeFalse();
        });

        it('can update regular', function () {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function () {
        it('can update itself', function () {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $regular))->toBeTrue();
        });

        it('cannot update admin', function () {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $admin))->toBeFalse();
        });

        it('cannot update staff', function () {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $staff))->toBeFalse();
        });

        it('cannot update other regular users', function () {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->update($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('delete', function () {
    describe('admin', function () {
        it('can delete itself', function () {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $admin))->toBeTrue();
        });

        it('can delete staff', function () {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $staff))->toBeTrue();
        });

        it('can delete regular', function () {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $regular))->toBeTrue();
        });

        it('cannot delete themselves', function () {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($admin, $otherAdmin))->toBeFalse();
        });
    });

    describe('staff', function () {
        it('can delete itself', function () {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $staff))->toBeTrue();
        });

        it('cannot delete themselves', function () {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $otherStaff))->toBeFalse();
        });

        it('cannot delete admin', function () {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $admin))->toBeFalse();
        });

        it('can delete regular', function () {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function () {
        it('can delete itself', function () {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $regular))->toBeTrue();
        });

        it('cannot delete admin', function () {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $admin))->toBeFalse();
        });

        it('cannot delete staff', function () {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $staff))->toBeFalse();
        });

        it('cannot delete themselves', function () {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->delete($regular, $otherRegular))->toBeFalse();
        });
    });
});

describe('restore', function () {
    describe('admin', function () {
        it('cannot restore itself', function () {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $admin))->toBeFalse();
        });

        it('can restore staff', function () {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $staff))->toBeTrue();
        });

        it('can restore regular', function () {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function () {
        it('cannot restore itself', function () {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $staff))->toBeFalse();
        });

        it('cannot restore other staff', function () {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $otherStaff))->toBeFalse();
        });

        it('cannot restore admin', function () {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $admin))->toBeFalse();
        });

        it('can restore regular', function () {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($staff, $regular))->toBeTrue();
        });
    });

    describe('regular', function () {
        it('cannot restore itself', function () {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $regular))->toBeFalse();
        });

        it('cannot restore other regular', function () {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $otherRegular))->toBeFalse();
        });

        it('cannot restore admin', function () {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $admin))->toBeFalse();
        });

        it('cannot restore staff', function () {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->restore($regular, $staff))->toBeFalse();
        });
    });
});

describe('forceDelete', function () {
    describe('admin', function () {
        it('cannot force delete itself', function () {
            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $admin))->toBeFalse();
        });

        it('cannot force delete other admin', function () {
            $admin = User::factory()->adminRole()->create();

            $otherAdmin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $otherAdmin))->toBeFalse();
        });

        it('can force delete staff', function () {
            $admin = User::factory()->adminRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $staff))->toBeTrue();
        });

        it('can force delete regular', function () {
            $admin = User::factory()->adminRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($admin, $regular))->toBeTrue();
        });
    });

    describe('staff', function () {
        it('cannot force delete itself', function () {
            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $staff))->toBeFalse();
        });

        it('cannot force delete other staff', function () {
            $staff = User::factory()->staffRole()->create();

            $otherStaff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $otherStaff))->toBeFalse();
        });

        it('cannot force delete admin', function () {
            $staff = User::factory()->staffRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $admin))->toBeFalse();
        });

        it('cannot force delete regular', function () {
            $staff = User::factory()->staffRole()->create();

            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($staff, $regular))->toBeFalse();
        });
    });

    describe('regular', function () {
        it('cannot force delete itself', function () {
            $regular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $regular))->toBeFalse();
        });

        it('cannot force delete other regular', function () {
            $regular = User::factory()->regularRole()->create();

            $otherRegular = User::factory()->regularRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $otherRegular))->toBeFalse();
        });

        it('cannot force delete admin', function () {
            $regular = User::factory()->regularRole()->create();

            $admin = User::factory()->adminRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $admin))->toBeFalse();
        });

        it('cannot force delete staff', function () {
            $regular = User::factory()->regularRole()->create();

            $staff = User::factory()->staffRole()->create();

            $policy = new UserPolicy();

            expect($policy->forceDelete($regular, $staff))->toBeFalse();
        });
    });
});
