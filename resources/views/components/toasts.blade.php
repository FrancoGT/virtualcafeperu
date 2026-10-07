{{--
    Alertas con SweetAlert2 para toda la app (tienda y admin).

    - session('swal')  => ['icon', 'title', 'text']  → modal (desde controladores)
    - session('error') => 'mensaje'                  → modal de error
    - Evento de navegador "notify" {type, message}   → toast (Livewire: dispatchBrowserEvent('notify', ...))
    - Evento de navegador "swal"   {icon, title, text} → modal (Livewire: dispatchBrowserEvent('swal', ...))
    - window.confirmAction({title, text, ...})       → Promise<boolean>, reemplaza a confirm()
    - <form data-confirm="¿Seguro?">                 → pide confirmación antes de enviar
      (opcionales: data-confirm-title, data-confirm-button, data-confirm-icon)
--}}
@php
    $flash = session('swal');
    $error = session('error');
@endphp

@once
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .swal2-popup { font-family: 'Nunito', ui-sans-serif, system-ui, sans-serif; border-radius: 1.25rem; padding-bottom: 1.5rem; }
        .swal2-title { color: #0f172a; font-weight: 800; font-size: 1.375rem; }
        .swal2-html-container { color: #64748b; font-size: .95rem; }
        .swal2-actions { gap: .5rem; }
        .swal2-styled { border-radius: 9999px !important; font-weight: 700 !important; padding: .625rem 1.5rem !important; margin: 0 !important; }
        .swal2-styled:focus { box-shadow: 0 0 0 3px rgba(249, 115, 22, .35) !important; }
        /* El reset de Tailwind ([type='button'] { background-color: transparent }) gana a los selectores
           :where() de SweetAlert2 y deja los botones en blanco; estas reglas tienen más especificidad. */
        .swal2-popup .swal2-actions button.swal2-confirm { background-color: #f97316; color: #fff; }
        .swal2-popup .swal2-actions button.swal2-confirm:hover { background-color: #ea580c; }
        .swal2-popup .swal2-actions button.swal2-cancel { background-color: #f1f5f9; color: #0f172a; box-shadow: inset 0 0 0 1px #e2e8f0; }
        .swal2-popup .swal2-actions button.swal2-cancel:hover { background-color: #e2e8f0; }
        .swal2-popup .swal2-actions button.swal2-deny { background-color: #ef4444; color: #fff; }
        .swal2-popup .swal2-actions button.swal2-deny:hover { background-color: #dc2626; }
        .swal2-toast.swal2-popup { border-radius: 1rem; padding: .75rem 1rem; box-shadow: 0 2px 4px rgba(15, 23, 42, .06), 0 12px 32px rgba(15, 23, 42, .12); }
        .swal2-toast .swal2-title { font-size: .9rem; font-weight: 700; color: #0f172a; margin: 0 .5rem; }
        .swal2-timer-progress-bar { background: rgba(249, 115, 22, .6); }
        div:where(.swal2-container) { z-index: 9999; }
        .swal-detail { margin-top: 1rem; text-align: left; font-size: .8rem; }
        .swal-detail summary { cursor: pointer; color: #94a3b8; font-weight: 600; text-align: center; list-style: none; }
        .swal-detail summary::-webkit-details-marker { display: none; }
        .swal-detail summary:hover { color: #64748b; }
        .swal-detail pre { margin-top: .5rem; max-height: 10rem; overflow: auto; white-space: pre-wrap; word-break: break-word; background: #f1f5f9; color: #475569; border-radius: .75rem; padding: .75rem; font-size: .75rem; }
    </style>

    <script>
        (function () {
            // Colores de los botones: ver el <style> de arriba
            const brand = { reverseButtons: true };
            const icons = ['success', 'error', 'warning', 'info', 'question'];
            const iconOf = (type) => icons.includes(type) ? type : 'info';

            const Toast = Swal.mixin({
                toast: true,
                position: window.matchMedia('(min-width: 640px)').matches ? 'top-end' : 'bottom',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (el) => {
                    el.addEventListener('mouseenter', Swal.stopTimer);
                    el.addEventListener('mouseleave', Swal.resumeTimer);
                },
            });

            window.toast = (type, message) => Toast.fire({ icon: iconOf(type), title: message });

            // Texto amigable + detalle técnico opcional plegado (se arma con textContent, sin HTML)
            const body = (text, detail) => {
                if (!detail) return undefined;
                const wrap = document.createElement('div');
                const p = document.createElement('p');
                p.textContent = text;
                const details = document.createElement('details');
                details.className = 'swal-detail';
                const summary = document.createElement('summary');
                summary.textContent = 'Ver detalle técnico';
                const pre = document.createElement('pre');
                pre.textContent = detail;
                details.append(summary, pre);
                wrap.append(p, details);
                return wrap;
            };

            window.alertModal = ({ icon = 'success', title = '', text = '', detail = '' } = {}) => Swal.fire({
                ...brand,
                icon: iconOf(icon),
                title,
                text: detail ? undefined : text,
                html: body(text, detail),
                confirmButtonText: 'Entendido',
                timer: icon === 'success' ? 2800 : undefined,
                timerProgressBar: icon === 'success',
            });

            window.confirmAction = ({ title = '¿Estás seguro?', text = '', icon = 'warning', confirmText = 'Sí, continuar', cancelText = 'Cancelar' } = {}) =>
                Swal.fire({
                    ...brand,
                    icon,
                    title,
                    text,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    focusCancel: true,
                }).then((r) => r.isConfirmed);

            // Eventos desde Livewire (dispatchBrowserEvent) o JS
            window.addEventListener('notify', (e) => window.toast(e.detail.type, e.detail.message));
            window.addEventListener('swal', (e) => window.alertModal(e.detail));

            // <form data-confirm="..."> pide confirmación antes de enviarse
            document.addEventListener('submit', (e) => {
                const form = e.target;
                if (!form.dataset || !form.dataset.confirm || form.dataset.confirmed) return;
                e.preventDefault();
                window.confirmAction({
                    text: form.dataset.confirm,
                    title: form.dataset.confirmTitle || undefined,
                    confirmText: form.dataset.confirmButton || undefined,
                    icon: form.dataset.confirmIcon || undefined,
                })
                    .then((ok) => { if (ok) { form.dataset.confirmed = '1'; form.requestSubmit ? form.requestSubmit() : form.submit(); } });
            }, true);
        })();
    </script>
@endonce

@if ($flash || $error)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if ($error)
                window.alertModal({ icon: 'error', title: 'Algo salió mal', text: @js($error) });
            @else
                window.alertModal(@js([
                    'icon' => $flash['icon'] ?? 'success',
                    'title' => $flash['title'] ?? '',
                    'text' => $flash['text'] ?? '',
                    'detail' => $flash['detail'] ?? '',
                ]));
            @endif
        });
    </script>
@endif
