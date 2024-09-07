function showEditModal(student) {
    document.getElementById('edit_student_number').value = student.student_number;
    document.getElementById('edit_first_name').value = student.first_name;
    document.getElementById('edit_last_name').value = student.last_name;
    document.getElementById('edit_middle_name').value = student.middle_name;
    document.getElementById('edit_date_of_birth').value = student.date_of_birth;
    document.getElementById('edit_gender').value = student.gender;
    document.getElementById('edit_course').value = student.course;

    const form = document.getElementById('editForm');
    form.action = `/students/${student.id}`;

    const editModal = new bootstrap.Modal(document.getElementById('editStudentModal'));
    editModal.show();
}

function showDeleteModal(sectionId) {
    const form = document.getElementById('deleteForm');
    form.action = `/sections/${sectionId}`;

    const deleteModal = new bootstrap.Modal(document.getElementById('deleteSectionModal'));
    deleteModal.show();
}