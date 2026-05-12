<!-- Notification Overlay Modal -->
<div id="notificationModal" class="hidden fixed z-50">
    <!-- Modal -->
    <div class="relative z-50 w-96 max-h-96 bg-white rounded-2xl shadow-2xl flex flex-col transform transition-all duration-300 ease-out scale-95 opacity-0 origin-top-right"
         id="notificationModalContent">
        <!-- Header -->
        <div class="sticky top-0 flex items-center justify-between p-6 border-b border-slate-200 bg-white rounded-t-2xl">
            <h2 class="text-xl font-bold">Notifikasi</h2>
            <button id="closeNotificationModal" class="p-2 hover:bg-slate-100 rounded-lg transition">
                <span class="material-symbols-outlined text-slate-600">close</span>
            </button>
        </div>

        <!-- Content -->
        <div id="notificationContent" class="flex-1 overflow-y-auto divide-y">
            <div class="flex items-center justify-center h-24 text-slate-500">
                <span class="material-symbols-outlined mr-2">hourglass_empty</span>
                Loading...
            </div>
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 p-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <a href="{{ route('notifications.index') }}" class="block w-full text-center py-2 px-4 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold rounded-lg transition">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
    const notificationModal = document.getElementById('notificationModal');
    const notificationModalContent = document.getElementById('notificationModalContent');
    const closeBtn = document.getElementById('closeNotificationModal');
    const notificationContent = document.getElementById('notificationContent');

    // Open notification modal
    function openNotificationModal() {
        const bell = document.getElementById('notificationTrigger');
        const rect = bell.getBoundingClientRect();
        
        // Position modal at top-right near the bell
        notificationModal.style.top = (rect.bottom + 8) + 'px';
        notificationModal.style.right = (window.innerWidth - rect.right) + 'px';
        
        notificationModal.classList.remove('hidden');
        
        // Trigger animation on next frame
        requestAnimationFrame(() => {
            notificationModalContent.classList.remove('scale-95', 'opacity-0');
            notificationModalContent.classList.add('scale-100', 'opacity-100');
        });
        
        fetchNotifications();
    }

    // Close notification modal
    function closeNotificationModal() {
        notificationModalContent.classList.add('scale-95', 'opacity-0');
        notificationModalContent.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            notificationModal.classList.add('hidden');
        }, 300);
    }

    // Fetch notifications via AJAX
    async function fetchNotifications() {
        try {
            const response = await fetch('{{ route('api.notifications') }}');
            const data = await response.json();

            if (data.notifications.length === 0) {
                notificationContent.innerHTML = `
                    <div class="p-12 text-center">
                        <div class="mx-auto w-16 h-16 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-4xl">notifications</span>
                        </div>
                        <h3 class="text-lg font-bold">Tidak ada notifikasi</h3>
                        <p class="text-sm text-slate-500 mt-2">Kondisi keuangan kamu belum memunculkan peringatan apa pun.</p>
                    </div>
                `;
                return;
            }

            notificationContent.innerHTML = data.notifications.map(notif => `
                <div class="p-4 flex gap-4 hover:bg-slate-50 transition">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 ${getNotificationIconClass(notif.type)}">
                        <span class="material-symbols-outlined text-sm">${getNotificationIcon(notif.type)}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between gap-2 mb-1">
                            <h3 class="font-semibold text-sm ${getNotificationTitleClass(notif.type)}">
                                ${notif.title}
                            </h3>
                            <span class="text-xs text-slate-400 flex-shrink-0">${notif.time}</span>
                        </div>
                        <p class="text-sm text-slate-600 mb-2">${notif.message}</p>
                        ${notif.action_label && notif.action_url ? `
                            <a href="${notif.action_url}" class="text-xs font-semibold text-emerald-700 hover:underline inline-block">
                                ${notif.action_label}
                            </a>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Error fetching notifications:', error);
            notificationContent.innerHTML = `
                <div class="p-12 text-center">
                    <span class="material-symbols-outlined text-4xl text-red-500 mb-4 block">error</span>
                    <p class="text-sm text-slate-500">Gagal memuat notifikasi</p>
                </div>
            `;
        }
    }

    function getNotificationIcon(type) {
        const icons = {
            'success': 'check_circle',
            'info': 'info',
            'warning': 'warning',
            'danger': 'error',
        };
        return icons[type] || 'info';
    }

    function getNotificationIconClass(type) {
        const classes = {
            'success': 'bg-emerald-100 text-emerald-700',
            'info': 'bg-blue-100 text-blue-700',
            'warning': 'bg-amber-100 text-amber-700',
            'danger': 'bg-red-100 text-red-700',
        };
        return classes[type] || 'bg-blue-100 text-blue-700';
    }

    function getNotificationTitleClass(type) {
        const classes = {
            'success': 'text-emerald-800',
            'info': 'text-blue-800',
            'warning': 'text-amber-800',
            'danger': 'text-red-800',
        };
        return classes[type] || 'text-blue-800';
    }

    // Event listeners
    closeBtn.addEventListener('click', closeNotificationModal);

    // Click notification bell icon
    const notificationBell = document.getElementById('notificationTrigger');
    if (notificationBell) {
        notificationBell.addEventListener('click', (e) => {
            e.preventDefault();
            if (notificationModal.classList.contains('hidden')) {
                openNotificationModal();
            } else {
                closeNotificationModal();
            }
        });
    }

    // Close modal when pressing Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !notificationModal.classList.contains('hidden')) {
            closeNotificationModal();
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', (e) => {
        const bell = document.getElementById('notificationTrigger');
        
        // Jangan tutup jika klik di bell button atau di dalam modal
        if (notificationModal.contains(e.target) || bell.contains(e.target)) {
            return;
        }
        
        if (!notificationModal.classList.contains('hidden')) {
            closeNotificationModal();
        }
    });
</script>
