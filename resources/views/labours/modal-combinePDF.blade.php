
<div class="card">
    <div class="card-body">
        <h4>CombinePDF</h4>
        <hr>
        <div class="container">
            <form action="{{ route('merge-pdfs') }}" method="post" id="combineForm">
                @csrf
                <input type="hidden" name="labour_id" value="{{ $labourModel->labour_id }}">
                <table>
                    @foreach ($labourfiles as $item)
                        @if ($item->labour_file_path)
                            <tr>
                                <td><input type="number" class="form-control no" name="no[]" placeholder="ลำดับ"
                                    style="width: 100px" readonly></td>
                                <td>
                                    &nbsp;
                                    <input type="checkbox" name="checkNum[]" class="checkNum" value="{{ asset('storage/LABOURS/' . $labourModel->labour_path . '/' . $item->labour_file_path) }}">
                                    <a href="{{ asset('storage/LABOURS/' . $labourModel->labour_path . '/' . $item->labour_file_path) }}"
                                       onclick="openPdfPopup(this.href); return false;">
                                        <i class="fas fa-file-pdf text-danger"></i> {{ $item->labour_file_path }}
                                    </a>
                                </td>
                                <td>[ {{ $item->labour_file_note }} ]</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="submit" class="btn btn-danger">Combine PDF</button>
                    <button type="button" id="downloadZipBtn" class="btn btn-primary">Download ZIP</button>
                </div>
            </form>
            
         </div>
    </div>


    <script>
 
        $(document).ready(function() {
            // Array to keep track of selected row indices in order of selection
            let selectedIndices = [];

            // Function to update sequence numbers
            function updateSequenceNumbers() {
                // Create a dictionary to map row index to sequence number
                let sequenceMap = {};

                // Assign sequence numbers based on the order of selection
                selectedIndices.forEach((index, i) => {
                    sequenceMap[index] = i + 1;
                });

                // Update sequence numbers in the table
                $('tr').each(function() {
                    let rowIndex = $(this).index();
                    if (sequenceMap[rowIndex] !== undefined) {
                        $(this).find('input.no').val(sequenceMap[rowIndex]);
                    } else {
                        $(this).find('input.no').val('');
                    }
                });
            }

            // Event handler for checkbox change
            $('input.checkNum').change(function() {
                let rowIndex = $(this).closest('tr').index();

                // Add or remove row index based on whether the checkbox is checked
                if ($(this).is(':checked')) {
                    if (!selectedIndices.includes(rowIndex)) {
                        selectedIndices.push(rowIndex);
                    }
                } else {
                    selectedIndices = selectedIndices.filter(index => index !== rowIndex);
                }

                // Update sequence numbers
                updateSequenceNumbers();
            });
        });
 
    </script>


<script>
    document.getElementById('downloadZipBtn').addEventListener('click', function () {
        const form = document.getElementById('combineForm');
        const formData = new FormData(form);
        fetch("{{ route('download.zip') }}", {
            method: "POST",
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Download failed");
            return response.blob();
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "selected-files.zip";
            document.body.appendChild(a);
            a.click();
            a.remove();
        })
        .catch(error => alert("ไม่สามารถดาวน์โหลดไฟล์ ZIP ได้"));
    });
</script>