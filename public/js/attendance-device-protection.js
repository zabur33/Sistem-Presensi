// Enhanced attendance API with device conflict handling
async function postAttendance(url, data) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            
            // Handle device conflict (409)
            if (response.status === 409 && errorData.conflict) {
                handleDeviceConflict(errorData);
                return null;
            }
            
            throw new Error(errorData.error || `HTTP ${response.status}`);
        }
        
        return await response.json();
    } catch (error) {
        console.error('Attendance API error:', error);
        throw error;
    }
}

// Handle device conflict
function handleDeviceConflict(errorData) {
    const message = errorData.error || 'Konflik device terdeteksi';
    const existingDevice = errorData.existing_device || 'Unknown';
    const existingTime = errorData.existing_time || 'Unknown';
    
    // Show conflict modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;
        z-index: 9999;
    `;
    
    modal.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 12px; max-width: 500px; text-align: center;">
            <div style="color: #dc2626; font-size: 48px; margin-bottom: 16px;">⚠️</div>
            <h3 style="color: #dc2626; margin-bottom: 16px;">Konflik Presensi</h3>
            <p style="margin-bottom: 8px; color: #374151;">${message}</p>
            <p style="font-size: 14px; color: #6b7280; margin-bottom: 20px;">
                Device ID: ${existingDevice}<br>
                Waktu Check-in: ${existingTime}
            </p>
            <button onclick="this.closest('div').parentElement.remove()" 
                    style="background: #dc2626; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                Tutup
            </button>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Disable all attendance buttons
    const attendanceButtons = document.querySelectorAll('[onclick*="handlePresensi"]');
    attendanceButtons.forEach(btn => {
        btn.disabled = true;
        btn.style.opacity = '0.5';
        btn.title = 'Presensi tidak diizinkan karena konflik device';
    });
}

// Update existing handlePresensi functions to use new API
// This will replace the existing postJSON calls in presensi-kantor.blade.php and presensi-luar-kantor.blade.php
