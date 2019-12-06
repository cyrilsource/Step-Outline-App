<template>
<div>
    <div class="row">
        <section class="container col-md-4">
            <button data-toggle="modal" data-target="#basicExampleModal" type="button" class="mb-2 btn btn-primary">Add a segment</button>
            <div class="input-group stylish-input-group col-xs-3">
                <input style="width: ;" v-model="search" @keyup="searchIt" type="text" class="mb-2 form-control"  placeholder="Search" >
            </div>
        </section>
    </div>
<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a new segment</h5>
            </div>
            <div class="modal-body">
                <form  @submit.prevent="addTask()">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="task.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                    <div class="form-group">
                        <label for="description">Segment description</label>
                        <textarea  v-model="task.description" type="text" class="form-control" id="description" aria-describedby="description" placeholder="Segment description">task.description</textarea>
                    </div>
                    <div class="form-group">
                        <label for="title">You can choose order</label>
                        <input v-model="task.order" type="number" class="form-control" id="order" aria-describedby="order" placeholder="order">
                    </div>
                    <button type="submit" class="btn btn-primary">Add a new segment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<draggable :list="tasks" :options="{animation:200}" :element="'div'" class="row" @change="changeOrder">
    <section v-for="( task, index ) in tasks" v-bind:key="task.id" class="col-sm-6 col-md-3">
                <div class="card" style="margin-bottom: 2rem;">
                    <div class="card-body">
                        <h5 class="card-title">{{ task.title }}<span class="order" style="font-style: italic; font-size: 1rem; margin-right: 0.5rem;"> Order: {{ task.order }}</span><span @click="deleteTask(task.id)"  style="font-size: 14px; color: #FF3547;">
                                    <i class="fas fa-trash-alt"></i>
                                </span></h5>
                        <p class="card-text">{{ task.description }}</p>
                        <button @click="selectTask(task.id)"  data-toggle="modal" data-target="#Modal2"
                        type="button" class="mb-2 btn btn-primary">Edit</button>
                    </div>
                </div>
        </section>
</draggable>

<!-- Modal 2-->
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a new segment</h5>
            </div>
            <div class="modal-body">
                <form  @submit.prevent="editTask(task.id)">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="task.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                    <div class="form-group">
                        <label for="description">Segment description</label>
                        <textarea  v-model="task.description" type="text" class="form-control" id="description" aria-describedby="description" placeholder="Segment description">task.description</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Edit segment</button>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
</template>

<script>
    import draggable from 'vuedraggable'
    import editable from '../components/ContentEditable.vue'

    export default {
        components: {
            draggable,
            editable
        },
        data() {
            return {
                tasks: [],
                task: {
                    id: '',
                    title: '',
                    description: '',
                },
                task_id: '',
                search: '',
            }
        },

        created() {
            this.fetchTasks();
        },

        methods: {
            fetchTasks() {
                fetch('api/tasks')
                .then(res => res.json())
                .then(res => {
                    this.tasks = res.data
                })
            },
            addTask() {
                let count = this.tasks.length;
                console.log(count);
                fetch(`api/task/${count}`, {
                method: 'post',
                body: JSON.stringify(this.task),
                headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    this.fetchTasks();
                })
                .catch(err => console.log(err));
            },
            selectTask(id) {
                fetch(`api/task/${id}`)
                .then(res => res.json())
                .then(res => {
                    this.task = res.data
                })
            },
            editTask(id) {
                // Add
                fetch(`api/task/${id}`, {
                method: 'put',
                body: JSON.stringify(this.task),
                headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.fetchTasks();
                    this.clearForm();
                })
                .catch(err => console.log(err));
            },
            deleteTask(id) {
               if (confirm('Are you sure ?')) {
                    fetch(`api/task/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.fetchTasks()
                    })
                    .catch(err => alert(err))
               }
            },
            clearForm() {
                this.task.title = '';
                this.task.description = '';
                this.task.order = '';
            },
            changeOrder() {
                this.tasks.map( ( task, index ) => {
                    task.order = index + 1;
                })
                fetch('api/tasks', {
                method: 'put',
                body: JSON.stringify(this.tasks),
                headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(data => {
                    this.fetchTasks();
                })
                .catch(err => console.log(err));
            },
            searchIt: _.debounce(function(){
                let query = this.search
                fetch(`api/findTask/${query}`)
                .then(res => res.json())
                .then(res => {
                    this.tasks = res.data
                })
            }, 500)
        }
    }
</script>
