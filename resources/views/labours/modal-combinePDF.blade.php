<div class="card">
    <div class="card-body">
        <h4>CombinePDF</h4>
        <hr>
        <div class="container">
            <form action="{{ route('merge-pdfs') }}" id="test" method="post">
                @csrf
                <table>
                    @foreach ($labourfiles as $item)
                        @if ($item->labour_file_path)
                            <tr>
                                <td><input type="number" class="form-control no" name="no[]" placeholder="ลำดับ"
                                        style="width: 100px" readonly></td>
                                <td>&nbsp; <input type="checkbox" name="checkNum[]" class="checkNum" value="{{ $labourModel->labour_path }}\\{{ $item->labour_file_path }}">
                                    {{ $item->labour_file_path }}</td>
                                <td>[ {{ $item->labour_file_note }} ]</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
        
                <button type="submit" class="float-end btn btn-danger">CombinePDF</button>
        
            </form>
         </div>
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