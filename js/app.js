const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active');
});

const recordButton = document.getElementById('record-button');
const stopButton = document.getElementById('stop-button');
const audioPlayer = document.getElementById('audio-player');
const downloadLink = document.getElementById('download-link');
const uploadForm = document.getElementById('upload-form');
const voiceNoteInput = document.getElementById('voice-note-input');

let mediaRecorder;
let chunks = [];

if (recordButton) {
    recordButton.addEventListener('click', async () => {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.start();
        recordButton.disabled = true;
        stopButton.disabled = false;

        mediaRecorder.ondataavailable = (e) => {
            chunks.push(e.data);
        };

        mediaRecorder.onstop = () => {
            const blob = new Blob(chunks, { type: 'audio/webm' });
            chunks = [];
            const audioURL = URL.createObjectURL(blob);
            audioPlayer.src = audioURL;
            downloadLink.href = audioURL;
            downloadLink.download = 'recording.webm';
            downloadLink.style.display = 'block';

            const file = new File([blob], 'recording.webm', { type: 'audio/webm' });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            voiceNoteInput.files = dataTransfer.files;
            uploadForm.style.display = 'block';
        };
    });
}

if (stopButton) {
    stopButton.addEventListener('click', () => {
        mediaRecorder.stop();
        recordButton.disabled = false;
        stopButton.disabled = true;
    });
}

// Pell editor initialization
const editorElement = document.getElementById('editor');
if (editorElement) {
    const editor = pell.init({
        element: editorElement,
        onChange: html => {
            document.getElementById('note-input').value = html;
        },
        defaultParagraphSeparator: 'p',
        styleWithCSS: false,
        actions: [
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'heading1',
            'heading2',
            'paragraph',
            'quote',
            'olist',
            'ulist',
            'code',
            'line',
            'link'
        ],
    });
    // Set initial content if available (for edit_note.php)
    const initialContent = document.getElementById('note-input').value;
    if (initialContent) {
        editor.content.innerHTML = initialContent;
    }
}
