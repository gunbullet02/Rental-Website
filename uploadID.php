<h3>Upload Your ID</h3>
<form id="uploadForm">
  <input type="file" id="idFile" required />
  <label>
    <input type="checkbox" id="terms" required> I agree to the terms and conditions
  </label>
  <button type="submit">Upload</button>
</form>

<p id="statusMsg"></p>

<script>
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  
  const file = document.getElementById('idFile').files[0];
  const preset = 'user_id_upload';
  const cloudName = 'dsgpuansp';

  if (!file || !document.getElementById('terms').checked) {
    return alert("Please select a file and agree to the terms.");
  }

  const formData = new FormData();
  formData.append('file', file);
  formData.append('upload_preset', preset);

  const response = await fetch(`https://api.cloudinary.com/v1_1/${cloudName}/auto/upload`, {
    method: 'POST',
    body: formData
  });

  const data = await response.json();

  if (data.secure_url) {
    document.getElementById('statusMsg').innerText = 'Upload successful. Admin will review.';
    console.log('File URL:', data.secure_url);

    // Save URL to your database
    fetch('save_id_url.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        id_url: data.secure_url
      })
    })
    .then(res => res.text())
    .then(response => {
      console.log('Server response:', response);
    })
    .catch(error => {
      console.error('Error saving to DB:', error);
    });

  } else {
    document.getElementById('statusMsg').innerText = 'Upload failed.';
  }
});
</script>
