<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Template 7 - {{ $lead->getFullNameAttribute() }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Sarabun', sans-serif; background: #f5f5f5; padding: 20px; }
        .template-selector { position: fixed; top: 20px; right: 20px; z-index: 1000; background: white; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .resume-container { max-width: 210mm; min-height: 297mm; margin: 0 auto; background: white; padding: 40px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        @media print { body { background: white; padding: 0; } .template-selector, .no-print { display: none !important; } .resume-container { box-shadow: none; margin: 0; } }
    </style>
</head>
<body>
    <div class="template-selector no-print">
        <label class="form-label"><strong>เลอกฟอรม Resume:</strong></label>
        <select class="form-select" id="templateSelector" onchange="changeTemplate(this.value)">
            @for($i = 1; $i <= 14; $i++)
                <option value="{{ $i }}" {{ $template == $i ? 'selected' : '' }}>ฟอรมท {{ $i }}</option>
            @endfor
        </select>
        <button onclick="window.print()" class="btn btn-primary btn-sm mt-2 w-100"><i class="bi bi-printer"></i> พมพ</button>
    </div>
    <div class="resume-container">
        <div class="text-center mb-4"><h2 class="text-danger">Resume Template 7</h2><p class="text-muted">ฟอรมเปลารอการปรบแตง</p></div>
        <div class="alert alert-info"><h4>ขอมลผสมคร: {{ $lead->getFullNameAttribute() }}</h4><p class="mb-0">Lead ID: {{ $lead->lead_id }}</p><p class="mb-0">เบอรโทร: {{ $lead->lead_phone }}</p><p class="mb-0">Email: {{ $lead->lead_email ?? '-' }}</p></div>
        <div class="mt-4 p-4 border rounded"><h5> Template 7 - พรอมใหปรบแตง</h5><p>ฟอรมนพรอมใหคณปรบแตง Layout และเพมขอมลตามตองการ</p></div>
    </div>
    <script>function changeTemplate(templateNumber) { const currentUrl = new URL(window.location.href); currentUrl.searchParams.set('template', templateNumber); window.location.href = currentUrl.toString(); }</script>
</body>
</html>
