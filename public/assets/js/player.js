/**
 * ក្តឹបកូដ Academy — Video Learning Player JS
 */

document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('lessonVideoPlayer');
    const completeBtn = document.getElementById('btnToggleComplete');

    if (!window.LESSON_CONFIG) return;

    const { courseId, lessonId, lastWatched, isCompleted } = window.LESSON_CONFIG;

    // 1. Resume Video from last watched position
    if (video) {
        video.addEventListener('loadedmetadata', function () {
            if (lastWatched > 0 && lastWatched < video.duration - 5) {
                video.currentTime = lastWatched;
            }
        });

        // 2. Save Progress every 10 seconds
        let lastSave = 0;
        video.addEventListener('timeupdate', function () {
            const currentTime = Math.floor(video.currentTime);
            if (currentTime - lastSave >= 10) {
                lastSave = currentTime;
                saveProgress(courseId, lessonId, currentTime);
            }

            // Auto-complete if 90% watched and not already completed
            if (!window.LESSON_CONFIG.isCompleted && video.duration > 0) {
                if (video.currentTime / video.duration >= 0.90) {
                    toggleComplete(true);
                }
            }
        });

        video.addEventListener('ended', function () {
            toggleComplete(true);
        });
    }

    // 3. Mark Lesson Completed Manual Button
    if (completeBtn) {
        completeBtn.addEventListener('click', function () {
            const currentlyCompleted = this.classList.contains('btn-completed');
            toggleComplete(!currentlyCompleted);
        });
    }

    function toggleComplete(status) {
        fetch(window.APP_URL + '/api/lesson/complete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                course_id: courseId,
                lesson_id: lessonId,
                completed: status
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.LESSON_CONFIG.isCompleted = status;
                const sidebarItem = document.querySelector(`.curriculum-item[data-lesson-id="${lessonId}"]`);
                
                if (completeBtn) {
                    if (status) {
                        completeBtn.classList.remove('btn-outline-success');
                        completeBtn.classList.add('btn-success', 'btn-completed');
                        completeBtn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> បានរៀនចប់រួចរាល់';
                    } else {
                        completeBtn.classList.remove('btn-success', 'btn-completed');
                        completeBtn.classList.add('btn-outline-success');
                        completeBtn.innerHTML = '<i class="bi bi-circle me-1"></i> កំណត់ថារៀនចប់';
                    }
                }

                if (sidebarItem) {
                    const icon = sidebarItem.querySelector('.status-icon');
                    if (icon) {
                        if (status) {
                            icon.className = 'status-icon bi bi-check-circle-fill text-success';
                        } else {
                            icon.className = 'status-icon bi bi-play-circle text-muted';
                        }
                    }
                }
            }
        })
        .catch(err => console.error(err));
    }

    function saveProgress(cId, lId, sec) {
        fetch(window.APP_URL + '/api/lesson/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                course_id: cId,
                lesson_id: lId,
                seconds: sec
            })
        }).catch(err => console.error(err));
    }
});
