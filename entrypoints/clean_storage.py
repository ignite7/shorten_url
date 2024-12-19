""" Delete storage folders. """

# Utilities
import os
import shutil

# Is the user root?
if os.geteuid() != 0:
    raise Exception('You need to be root for this operation.')

# Paths
PUBLIC_PATH = './public/storage'

# Storage
check_public = os.path.exists(PUBLIC_PATH)

if check_public:
    shutil.rmtree(PUBLIC_PATH)
    print('Public Storage deleted.')
else:
    print('Nothing to delete!')
