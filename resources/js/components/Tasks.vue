<template>
<div>
    <div class="container header">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <section class="pilot col-md-12">
                        <div class="col-md-12">
                            <button @click="clearForm()" data-toggle="modal" data-target="#basicExampleModal" type="button" class="mb-2 btn btn-primary">Add a segment</button>
                            <button@click="clearForm()" data-toggle="modal" data-target="#modal3" type="button" class="mb-2 btn btn-primary">Add a marker</button>
                            <button@click="clearForm()" data-toggle="modal" data-target="#modal4" type="button" class="mb-2 btn btn-primary">Add a color</button>
                        </div>
                        <div class="input-group stylish-input-group col-xs-3">
                            <input  v-model="search" @keyup="searchIt" type="text" class="mb-2 form-control"  placeholder="Search" >
                        </div>
                        <form class="range-field my-4 w-25 d-flex justify-content-start flex-nowrap align-items-center">
                            <label>Width:</label>
                            <input type="range" min="100" max="400" v-model="sliderValue" @input="changeWidth"/>
                        </form>
                        <form class="range-field my-4 w-25 d-flex justify-content-start flex-nowrap align-items-center">
                            <label>Height:</label>
                            <input type="range" min="100" max="300" v-model="sliderValue2" @input="changeHeight"/>
                        </form>
                    </section>
                </div>
            </div>
            <div class="col-md-6 colors_list">
                <table class="table">
                    <tbody>
                        <tr v-for="( color ) in colors" v-bind:key="color.id">
                            <td v-bind:style="{backgroundColor: color.color }"></td>
                            <td><h4>{{ color.title }}</h4></td>
                            <td><p>{{ color.description }}</p></td>
                            <td><button @click="selectColor(color.id)"  data-toggle="modal" data-target="#modal5" type="button" class="mb-2 btn btn-primary">Edit Color</button></td>
                            <td><button @click="deleteColor(color.id)"   type="button" class="mb-2 btn btn-danger">Delete Color</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

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
                <form  @submit.prevent="addTask('segment')">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="task.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                    <div class="form-group">
                        <label for="description">Segment description</label>
                        <textarea  v-model="task.description" type="text" class="form-control" id="description" aria-describedby="description" placeholder="Segment description">task.description</textarea>
                    </div>
                    <div class="form-group">
                        <label for="order">You can choose order</label>
                        <input v-model="task.order" min="0" type="number" class="form-control" id="order" aria-describedby="order" placeholder="order">
                    </div>
                    <div class="form-group">
                        <label for="color">You can choose a background color</label>
                        <select  name="color" v-model="task.color_id" class="browser-default custom-select">
                            <option selected value="" style="color: red !important;">none </option>
                            <option v-for="( color ) in colors" v-bind:key="color.id"  v-bind:style="{color: color.color }" v-bind:value="color.id">{{ color.title }} </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add a new segment</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <draggable v-if="search === ''" :list="tasks" :options="{animation:200}" :element="'div'" class="timeline d-flex justify-content-start flex-nowrap" @change="changeOrder">
        <section v-for="( task) in tasks" v-bind:key="task.id" class="d-flex justify-content-start flex-nowrap align-items-center">
            <div v-if="task.type === 'segment' && task.color_id !== null"   class="card p-20" style="margin-bottom: 2rem;" v-bind:style="{width: computedWidth, height: computedHeight, backgroundColor: task.color.color }">
                <div class="card-body" @click="selectTask(task.id)"  data-toggle="modal" data-target="#Modal2">
                    <h2 class="card-title">{{ task.title }}</h2>
                    <p class="order" style="font-style: italic; font-size: 1rem; margin-right: 0.5rem;> "> Order: {{ task.order }}</p>
                    <p class="card-text">{{ task.description }}</p>
                </div>
                <span class="trash_card" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else-if="task.type === 'segment' && task.color_id === null"   class="card p-20" style="margin-bottom: 2rem;" v-bind:style="{width: computedWidth, height: computedHeight, backgroundColor: '#fcfcfc' }">
                <div class="card-body" @click="selectTask(task.id)"  data-toggle="modal" data-target="#Modal2">
                    <h2 class="card-title">{{ task.title }}</h2>
                    <p class="order" style="font-style: italic; font-size: 1rem; margin-right: 0.5rem;> "> Order: {{ task.order }}</p>
                    <p class="card-text">{{ task.description }}</p>
                </div>
                <span class="trash_card" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else-if="task.type === 'column' && task.color_id !== null">
                <div class="column_body" v-bind:style="{backgroundColor: task.color.color }" data-toggle="modal" data-target="#Modal2" @click="selectTask(task.id)">

                </div>
                <span class="trash_column" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else >
                <div class="column_body" v-bind:style="{backgroundColor: '#fcfcfc' }" data-toggle="modal" data-target="#Modal2" @click="selectTask(task.id)">

                </div>
                <span class="trash_column" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        </section>
    </draggable>


    <div v-if="search.length > 0" class="timeline d-flex justify-content-start flex-nowrap">
        <section v-for="( task) in tasks" v-bind:key="task.id" class="d-flex justify-content-start flex-nowrap align-items-center">
            <div v-if="task.type === 'segment' && task.color_id !== null"   class="card p-20" style="margin-bottom: 2rem;" v-bind:style="{width: computedWidth, height: computedHeight, backgroundColor: task.color.color}">
                <div class="card-body" @click="selectTask(task.id)"  data-toggle="modal" data-target="#Modal2">
                    <h2 class="card-title">{{ task.title }}</h2>
                    <p class="order" style="font-style: italic; font-size: 1rem; margin-right: 0.5rem;> "> Order: {{ task.order }}</p>
                    <p class="card-text">{{ task.description }}</p>
                </div>
                <span class="trash_card" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else-if="task.type === 'segment' && task.color_id === null"   class="card p-20" style="margin-bottom: 2rem;" v-bind:style="{width: computedWidth, height: computedHeight, backgroundColor: '#fcfcfc' }">
                <div class="card-body" @click="selectTask(task.id)"  data-toggle="modal" data-target="#Modal2">
                    <h2 class="card-title">{{ task.title }}</h2>
                    <p class="order" style="font-style: italic; font-size: 1rem; margin-right: 0.5rem;> "> Order: {{ task.order }}</p>
                    <p class="card-text">{{ task.description }}</p>
                </div>
                <span class="trash_card" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else-if="task.type === 'column' && task.color_id !== null">
                <div class="column_body" v-bind:style="{backgroundColor: task.color.color }" data-toggle="modal" data-target="#Modal2" @click="selectTask(task.id)">

                </div>
                <span class="trash_column" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
            <div v-else >
                <div class="column_body" v-bind:style="{backgroundColor: '#fcfcfc' }" data-toggle="modal" data-target="#Modal2" @click="selectTask(task.id)">

                </div>
                <span class="trash_column" @click="deleteTask(task.id)"  style="font-size: 20px; color: #FF3547;">
                    <i class="fas fa-times"></i>
                </span>
            </div>
        </section>
    </div>

<!-- Modal 2-->
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit segment</h5>
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
                     <div class="form-group">
                        <label  for="color">You can choose a background color</label>
                        <select  v-if="task.color_id !== null" v-model="task.color_id" class="browser-default custom-select">
                            <option  v-for="( color ) in colors" v-bind:key="color.id" :selected="color.id == task.color_id"
                            v-bind:style="{color: color.color }" v-bind:value="color.id">{{ color.title }}</option>
                            <option value="" style="color: red !important;">none </option>
                        </select>
                        <select v-if="task.color_id === null" v-model="task.color_id" class="browser-default custom-select">
                            <option selected value="">none </option>
                            <option  v-for="( color ) in colors" v-bind:key="color.id"
                            v-bind:style="{color: color.color }" v-bind:value="color.id">{{ color.title }}</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Edit segment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal 3-->
<div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add marker</h5>
            </div>
            <div class="modal-body">
                <form  @submit.prevent="addTask('column')">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="task.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                     <div class="form-group">
                        <label for="title">You can choose order</label>
                        <input v-model="task.order" min="0" type="number" class="form-control" id="order" aria-describedby="order" placeholder="order">
                    </div>
                    <div class="form-group">
                        <label for="color">You can choose a background color</label>
                        <select  v-model="task.color_id" class="browser-default custom-select">
                            <option selected value="" style="color: red !important;">none </option>
                            <option v-for="( color ) in colors" v-bind:key="color.id"  v-bind:style="{color: color.color }" v-bind:value="color.id">{{ color.title }} </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add marker</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal 4-->
<div class="modal fade" id="modal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add color</h5>
            </div>
            <div class="modal-body">
                <form  @submit.prevent="addColor()">
                    <div class="form-group">
                        <label for="color">Choose a color</label>
                        <input v-model="color.color" type="color" class="form-control" id="color" aria-describedby="color" placeholder="color">
                    </div>
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="color.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea  v-model="color.description" type="text" class="form-control" id="description" aria-describedby="description" placeholder="Segment description">task.description</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add color</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal 5-->
<div class="modal fade" id="modal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit color</h5>
            </div>
            <div class="modal-body">
                <form  @submit.prevent="editColor(color.id)">
                    <label for="color">The color</label>
                        <input v-model="color.color" type="color" class="form-control" id="color" aria-describedby="color" placeholder="color">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input v-model="color.title" type="text" class="form-control" id="title" aria-describedby="title" placeholder="the title">
                    </div>
                    <div class="form-group">
                        <label for="description">Segment description</label>
                        <textarea  v-model="color.description" type="text" class="form-control" id="description" aria-describedby="description" placeholder="Segment description">task.description</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Edit color</button>
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
                    type: '',
                    order: '',
                    color_id:''
                },
                colors: [],
                color: {
                    id: '',
                    title: '',
                    description: '',
                    color: '',
                },
                task_id: '',
                search: '',
                sliderValue:150,
                width: 150,
                sliderValue2: 150,
                height: 150,
            }
        },

        created() {
            this.fetchTasks();
            this.fetchColors();
        },

        computed:{
            computedWidth(){
                return this.width + 'px';
            },
            computedHeight(){
                return this.height + 'px';
            }
        },

        methods: {
            changeWidth(){
                this.width = this.sliderValue
            },
            changeHeight(){
                this.height = this.sliderValue2
            },
            fetchTasks() {
                fetch('api/tasks')
                .then(res => res.json())
                .then(res => {
                    this.tasks = res.data
                })
            },
            fetchColors() {
                fetch('api/colors')
                .then(res => res.json())
                .then(res => {
                    this.colors = res.data
                })
            },
            addTask(type) {
                let count = this.tasks.length;
                fetch(`api/task/${count}/${type}`, {
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
            addColor() {
                fetch('api/color', {
                method: 'post',
                body: JSON.stringify(this.color),
                headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    this.fetchTasks();
                    this.fetchColors();
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
            selectColor(id) {
                fetch(`api/color/${id}`)
                .then(res => res.json())
                .then(res => {
                    this.color = res.data
                })
            },
            editTask(id) {
                fetch(`api/task/${id}`, {
                method: 'put',
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
            editColor(id) {
                fetch(`api/color/${id}`, {
                method: 'put',
                body: JSON.stringify(this.color),
                headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.clearForm();
                    this.fetchTasks();
                    this.fetchColors();

                })
                .catch(err => console.log(err));
            },
            deleteTask(id) {
                 let x = document.getElementById('Modal2')
                 x.style.display = "none"
               if (confirm('Are you sure ?')) {
                    fetch(`api/task/${id}`, {
                        method: 'delete',
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.fetchTasks()
                        this.fetchColors()
                        x.style.display = "none"
                    })
                    .catch(err => console.log(err))
               }
               else {
                   x.style.display = "none"
               }
            },
            deleteColor(id) {
               if (confirm('Are you sure ?')) {
                    fetch(`api/color/${id}`, {
                        method: 'delete',
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.fetchTasks()
                        this.fetchColors()
                    })
                    .catch(err => console.log(err))
               }
            },
            clearForm() {
                this.task.title = '';
                this.task.description = '';
                this.task.order = '';
                this.color.title = '';
                this.color.description = '';
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

<style>
.header {
    margin-top: 2rem;
    margin-bottom: 2rem;
}
.pilot {
    margin-bottom: 3rem;
}
.card {
    overflow: auto;
    transition: all 0.5s ease;
    height: 150px;
    word-break: keep-all;
    cursor: pointer;
    margin-right: 1rem;
    margin-left: 1rem;
    position: relative;
    resize: both;
}

.card p{
    color: black !important;
}

.card-body {
    min-width: 200px;
}

.card .trash_card {
    position: absolute;
    top: 0px;
    right: 2px;
}

.timeline {
    overflow: auto;
    padding: 2rem;
}

.column {
    position: relative;
    cursor: pointer;
}

.column_body {
    height: 300px;
    width: 25px;
}

.column .trash_column {
    position: absolute;
    bottom: 0;
    left: 5px;
}

.colors_list {
    height: 12rem;
    overflow: auto;
}

table.table td, table.table th {
    padding-top: 0rem;
    padding-bottom: 0rem;
}

.select_color {
    width: 50px;
    height: 50px;
}

</style>
