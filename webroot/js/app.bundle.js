(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{11:function(e,t){},15:function(e,t,i){e.exports=i(7)},6:function(e,t,i){"use strict";(function(e){i.d(t,"a",function(){return n});var a=i(0),s=i.n(a);const n={loadBeditaPlugins(){BEDITA.plugins.forEach(t=>{const i=window[t]||e[t];if(i){const e=i.default;Object.keys(e).forEach(i=>{"object"==typeof e[i]&&(s.a.component(i,e[i]),console.debug(`%c[${i}]%c component succesfully registred from %c${t}%c Plugin`,"color: blue","color: black","color: red","color: black"))})}})}}}).call(this,i(2))},7:function(e,t,i){"use strict";i.r(t);var a=i(0),s=i.n(a);s.a.filter("humanize",function(e){return e.split("_").map(function(e){return e.charAt(0).toUpperCase()+e.substring(1)}).join(" ")});const n={devtools:!0},o={delimiters:["<:",":>"]};for(let e in n)n.hasOwnProperty(e)&&(s.a.config[e]=n[e]);for(let e in o)o.hasOwnProperty(e)&&(s.a.options[e]=o[e]);const r={configFull:{toolbar:[{name:"document",groups:["mode"],items:["Source"]},{name:"basicstyles",groups:["basicstyles","cleanup"],items:["Bold","Italic","Underline","Strike","Subscript","Superscript","-","RemoveFormat"]},{name:"paragraph",groups:["list","blocks","align"],items:["NumberedList","BulletedList","-","Blockquote","JustifyLeft","JustifyCenter","JustifyRight","JustifyBlock"]},{name:"links",items:["Link","Unlink","Anchor"]},{name:"editAttributes",items:["Attr"]},{name:"editing",groups:["find"],items:["Find","Replace"]},{name:"insert",items:["Image","Table","HorizontalRule","SpecialChar","Formula"]},{name:"tools",items:["ShowBlocks","AutoCorrect"]},{name:"styles",items:["Format","Styles"]},{name:"clipboard",groups:["clipboard","undo"],items:["Cut","Copy","Paste","PasteText","PasteFromWord","-","Undo","Redo"]}],allowedContent:!0,language:BEDITA.currLang2,entities:!1,fillEmptyBlocks:!1,forcePasteAsPlainText:!0,startupOutlineBlocks:!0,height:200},configNormal:{toolbar:[{name:"document",groups:["mode"],items:["Source"]},{name:"basicstyles",groups:["basicstyles","cleanup"],items:["Bold","Italic","Underline","Strike","-","RemoveFormat"]},{name:"links",items:["Link","Unlink"]},{name:"clipboard",groups:["clipboard","undo"],items:["PasteText","PasteFromWord","-","Undo","Redo"]}],allowedContent:!0,language:BEDITA.currLang2,entities:!1,fillEmptyBlocks:!1,forcePasteAsPlainText:!0,startupOutlineBlocks:!0,height:200},configSimple:{toolbar:[{name:"document",groups:["mode"],items:["Source"]},{name:"basicstyles",groups:["basicstyles","cleanup"],items:["Bold","Italic","Underline","Strike","Subscript","Superscript","-","RemoveFormat"]},{name:"links",items:["Link","Unlink"]},{name:"clipboard",groups:["clipboard","undo"],items:["Undo","Redo"]},{name:"tools",items:["ShowBlocks"]}],allowedContent:!0,language:BEDITA.currLang2,entities:!1,fillEmptyBlocks:!1,forcePasteAsPlainText:!0,startupOutlineBlocks:!0,height:200}};i(11);var l=i(6),d={props:{ids:{type:String,default:()=>[]}},data:()=>({allIds:[],selectedRows:[],status:""}),created(){try{this.allIds=JSON.parse(this.ids)}catch(e){console.error(e)}},computed:{selectedIds(){return JSON.stringify(this.selectedRows)},allChecked(){return JSON.stringify(this.selectedRows.sort())==JSON.stringify(this.allIds.sort())}},methods:{toggleAll(){this.allChecked?this.unCheckAll():this.checkAll()},checkAll(){this.selectedRows=JSON.parse(JSON.stringify(this.allIds))},unCheckAll(){this.selectedRows=[]},exportSelected(){this.selectedRows.length<1||document.getElementById("form-export").submit()},setStatus(e,t){this.selectedRows.length<1||(this.status=e,this.$nextTick(()=>{document.getElementById("form-status").submit()}))},trash(){this.selectedRows.length<1||confirm("Move "+this.selectedRows.length+" item to trash")&&document.getElementById("form-delete").submit()},selectRow(e){if("checkbox"!=e.target.type){e.preventDefault();var t=e.target.querySelector("input[type=checkbox]");let i=this.selectedRows.indexOf(t.value);-1!=i?this.selectedRows.splice(i,1):this.selectedRows.push(t.value)}}}};const h="staggered";var c={template:`\n        <transition-group appear\n            name="${h}"\n            v-on:enter="enter"\n            v-on:after-enter="afterEnter">\n            <slot></slot>\n        </transition-group>`,props:{stagger:{type:String,default:()=>50}},methods:{enter(e,t){e.classList.remove(`${h}-enter-to`),e.classList.add(`${h}-enter`);const i=this.getDelay(e);setTimeout(()=>{this.$nextTick(()=>{e.classList.add(`${h}-enter`),e.classList.remove(`${h}-enter-to`),e.classList.remove(`${h}-enter-active`)}),t()},i)},afterEnter(e){this.$nextTick(()=>{e.classList.remove(`${h}-enter`),e.classList.remove(`${h}-enter-to`)})},getDelay(e){return e.dataset&&e.dataset.index*this.stagger+5}}};const p={count:1,page:1,page_size:20,page_count:1},m={data:()=>({objects:[],endpoint:null,pagination:p,query:{},formatObjetsFilter:["params","priority","position","url"]}),methods:{getPaginatedObjects(e=!0,t={}){let i=window.location.href;if(this.endpoint){t&&(this.query=t);let a=`${i}/${this.endpoint}`;const s={credentials:"same-origin",headers:{accept:"application/json"}};return a=this.getUrlWithPaginationAndQuery(a),fetch(a,s).then(e=>e.json()).then(t=>{let i=(Array.isArray(t.data)?t.data:[t.data])||[];return t.data||(i=[]),e&&(this.objects=i),this.pagination=t.meta&&t.meta.pagination||this.pagination,i}).catch(e=>{console.error(e)})}return Promise.reject()},formatObjects(e){if(void 0===e)return[];const t=[];return e.forEach(e=>{let i={};i.id=e.id,i.type=e.type;const a=e.meta.relation;if(a){let e={};this.formatObjetsFilter.forEach(t=>{a[t]&&(e[t]=a[t])}),Object.keys(e).length&&(i.meta={relation:e})}t.push(i)}),t},setPagination(e){let t="",i="?";return Object.keys(this.pagination).forEach((e,i)=>{t+=`${i?"&":""}${e}=${this.pagination[e]}`}),-1===e.indexOf(i)||(i="&"),`${e}${i}${t}`},getUrlWithPaginationAndQuery(e){let t="",i="?";return Object.keys(this.pagination).forEach((e,i)=>{t+=`${i?"&":""}${e}=${this.pagination[e]}`}),t.length>1&&(t+="&"),Object.keys(this.query).forEach((e,i)=>{t+=`${i?"&":""}${e}=${this.query[e]}`}),-1===e.indexOf(i)||(i="&"),`${e}${i}${t}`},findObjectById(e){let t=this.objects.filter(t=>t.id===e);return t.length&&t[0]},async loadMore(e=p.page_size){if(this.pagination.page_items<this.pagination.count){let t=await this.nextPage(!1);this.pagination.page_items=this.pagination.page_items+e<=this.pagination.count?this.pagination.page_items+e:this.pagination.count;const i=this.objects.length;this.objects.splice(i,0,...t)}},toPage(e,t={}){return this.pagination.page=e||1,this.getPaginatedObjects(!0,t)},firstPage(e=!0){return 1!==this.pagination.page?(this.pagination.page=1,this.getPaginatedObjects(e)):Promise.resolve([])},lastPage(e=!0){return this.pagination.page!==this.pagination.page_count?(this.pagination.page=this.pagination.page_count,this.getPaginatedObjects(e)):Promise.resolve([])},nextPage(e=!0){return this.pagination.page<this.pagination.page_count?(this.pagination.page=this.pagination.page+1,this.getPaginatedObjects(e)):Promise.resolve([])},prevPage(){return this.pagination.page>1?(this.pagination.page=this.pagination.page-1,this.getPaginatedObjects()):Promise.resolve()},setPageSize(e){this.pagination.page_size=e,this.pagination.page=1}}};var g={mixins:[m],components:{StaggeredList:c},props:{relationName:{type:String,required:!0},viewVisibility:{type:Boolean,default:()=>!1},addedRelations:{type:Array,default:()=>[]},hideRelations:{type:Array,default:()=>[]}},computed:{keyEvents(){return{esc:{keyup:this.handleKeyboard}}}},data:()=>({method:"relationshipsJson",loading:!1,pendingRelations:[],relationsData:[],isVisible:!1}),created(){this.endpoint=`${this.method}/${this.relationName}`},watch:{addedRelations(e){this.pendingRelations=e},pendingRelations(e){this.relationsData=this.relationFormatterHelper(e)},viewVisibility(e){this.isVisible=e},isVisible(){this.objects.length||this.loadObjects(),this.$nextTick(()=>{this.isVisible&&this.$refs.inputFilter&&this.$refs.inputFilter.focus()}),this.$emit("visibility-setter",this.isVisible)},loading(e){this.$parent.$emit("loading",e)}},methods:{async loadObjects(){this.loading=!0;let e=await this.getPaginatedObjects();return this.loading=!1,e},appendRelations(){this.$emit("append-relations",this.pendingRelations),this.isVisible=!1},handleKeyboard(e){this.isVisible&&(e.stopImmediatePropagation(),e.preventDefault(),this.hideRelationshipModal())},hideRelationshipModal(){this.pendingRelations=this.addedRelations,this.isVisible=!1},hasElementsToShow(){return this.objects.filter(e=>!this.hideRelations.filter(t=>e.id===t.id).length).length},relationFormatterHelper(e,t){let i="";try{i=JSON.stringify(e)}catch(e){console.error(e)}return i},containsId:(e,t)=>e.filter(e=>e.id===t).length}},u={template:'\n        <nav class="pagination has-text-size-smallest" :class="pagination.count > 4 && \'show-pagination\'">\n\n            <div class="pagination-items">\n                <span><: pagination.page_items :> <: objectsLabel :></span>\n            </div>\n\n            <div class="filter-search">\n                <span>\n                    <input type="text" :placeholder="placeholder" v-model="filter" @keyup.enter.prevent.stop="applyFilter()"/>\n                </span>\n\n                <button v-show="showFilterButtons" name="applysearch" @click.prevent="applyFilter()"><: applyFilterLabel :></button>\n                <button v-show="showFilterButtons" name="resetsearch" @click.prevent="resetFilter()"><: resetFilterLabel :></button>\n            </div>\n\n            <div class="page-size">\n                <span><: pageSizesLabel :>:</span>\n            </div>\n\n            <div class="pagination-buttons">\n                <select class="page-size-selector has-background-gray-700 has-border-gray-700 has-text-gray-200 has-text-size-smallest has-font-weight-light" v-model="pageSize">\n                    <option v-for="size in paginateSizes"><: size :></option>\n                </select>\n\n                <button\n                    v-for="i in pagination.page_count" :key="i"\n                    v-if="pagination.page_count > 1"\n                    v-bind:class="pagination.page == i? \'\' : \'is-dark\'"\n                    v-bind:style="pagination.page == i? \'pointer-events: none\' : \'\'"\n                    class="has-text-size-smallest" @click.prevent="changePage(i)"><: i :></button>\n            </div>\n        </nav>\n    ',props:{applyFilterLabel:{type:String,default:"Apply"},resetFilterLabel:{type:String,default:"Reset"},objectsLabel:{type:String,default:"Objects"},pageSizesLabel:{type:String,default:"Size"},placeholder:{type:String,default:"Search"},showFilterButtons:{type:Boolean,default:!0},initFilter:{type:Object,default:()=>{}},pagination:{type:Object,default:()=>p},configPaginateSizes:{type:String,default:"[]"}},data(){return{filter:"",queryFilter:this.initFilter,timer:null,pageSize:this.pagination.page_size}},computed:{paginateSizes(){return JSON.parse(this.configPaginateSizes)}},watch:{pageSize(e){this.$emit("filter-update-page-size",this.pageSize)},filter(e){this.filter=e,this.queryFilter={q:this.filter},clearTimeout(this.timer),(e.length>=3||0==e.length)&&(this.timer=setTimeout(()=>{this.$emit("filter-objects",this.queryFilter)},300))}},mounted(){void 0!==this.queryFilter&&(this.filter=this.queryFilter.q||"")},methods:{applyFilter(){this.$emit("filter-objects-submit",this.queryFilter)},resetFilter(){this.$emit("filter-reset")},changePage(e){this.$emit("filter-update-current-page",e)}}},f={name:"tree-list",template:'\n        <div\n            class="tree-list-node"\n            :class="treeListMode">\n\n            <div v-if="!isRoot">\n                <div v-if="multipleChoice"\n                    class="node-element"\n                    :class="{\n                        \'tree-related-object\': isRelated,\n                        \'disabled\': isCurrentObjectInPath,\n                        \'node-folder\': isFolder,\n                    }">\n\n                    <span\n                        @click.prevent.stop="toggle"\n                        class="icon"\n                        :class="nodeIcon"\n                        ></span>\n                    <input\n                        type="checkbox"\n                        :value="item"\n                        v-model="related"\n                    />\n                    <label\n                        @click.prevent.stop="toggle"\n                        :class="isFolder ? \'is-folder\' : \'\'"><: caption :></label>\n                </div>\n                <div v-else class="node-element"\n                    :class="{\n                        \'tree-related-object\': isRelated || stageRelated,\n                        \'was-related-object\': isRelated && !stageRelated,\n                        \'disabled\': isCurrentObjectInPath\n                    }"\n\n                    @click.prevent.stop="select">\n                    <span\n                        @click.prevent.stop="toggle"\n                        class="icon"\n                        :class="nodeIcon"\n                        ></span>\n                    <label><: caption :></label>\n                </div>\n            </div>\n            <div :class="isRoot ? \'\' : \'node-children\'" v-show="open" v-if="isFolder">\n                <tree-list\n                    @add-relation="addRelation"\n                    @remove-relation="removeRelation"\n                    @remove-all-relations="removeAllRelations"\n                    v-for="(child, index) in item.children"\n                    :key="index"\n                    :item="child"\n                    :multiple-choice="multipleChoice"\n                    :related-objects="relatedObjects"\n                    :object-id=objectId>\n                </tree-list>\n            </div>\n        </div>\n    ',data:()=>({stageRelated:!1,related:!1,open:!0}),props:{multipleChoice:{type:Boolean,default:!0},captionField:{type:String,required:!1,default:"name"},childrenField:{type:String,required:!1,default:"children"},item:{type:Object,required:!0,default:()=>{}},relatedObjects:{type:Array,default:()=>[]},objectId:{type:String,required:!1}},computed:{caption(){return this.item[this.captionField]},isFolder(){return this.item.children&&!!this.item.children.length},isRoot(){return this.item.root||!1},isRelated(){return!!this.item.id&&!!this.relatedObjects.filter(e=>e.id===this.item.id).length},isCurrentObjectInPath(){return this.item&&this.item.object&&-1!==this.item.object.meta.path.indexOf(this.objectId)},nodeIcon(){let e="";return e+=this.isFolder?this.open?"icon-down-dir":"icon-right-dir":"unicode-branch"},treeListMode(){let e=[];return this.isRoot&&e.push("root-node"),this.multipleChoice?e.push("tree-list-multiple-choice"):e.push("tree-list-single-choice"),this.isCurrentObject&&e.push("disabled"),e.join(" ")}},watch:{related(e){this.stageRelated=e},stageRelated(e){this.item.object&&(e?this.$emit("add-relation",this.item.object):this.$emit("remove-relation",this.item.object))},relatedObjects(){this.related=this.isRelated}},methods:{toggle(){this.isFolder&&(this.open=!this.open)},addRelation(e){this.$emit("add-relation",e)},removeRelation(e){this.$emit("remove-relation",e)},removeAllRelations(){this.$emit("remove-all-relations")},select(){this.isCurrentObjectInPath||(this.$emit("remove-all-relations"),this.stageRelated=!this.stageRelated)}}},b=i(1),y={extends:g,components:{TreeList:f},props:{relatedObjects:{type:Array,default:()=>[]},loadOnStart:[Boolean,Number],multipleChoice:{type:Boolean,default:!0}},data:()=>({jsonTree:{}}),created(){this.loadTree()},watch:{pendingRelations(e){let t=e.filter(e=>!this.isRelated(e.id));this.multipleChoice||t.length&&(t=t[0]),this.relationsData=this.relationFormatterHelper(t);let i=this.relatedObjects.filter(e=>!this.isPending(e.id));this.$emit("remove-relations",i)},objects(){this.pendingRelations=this.objects.filter(e=>this.isRelated(e.id))}},methods:{async loadTree(){if(this.loadOnStart){var e="number"==typeof this.loadOnStart?this.loadOnStart:0;await Object(b.a)(e),await this.loadObjects(),this.jsonTree={name:"Root",root:!0,object:{},children:this.createTree()}}},addRelation(e){e&&void 0!==!e.id?this.containsId(this.pendingRelations,e.id)||this.pendingRelations.push(e):console.error("[addRelation] needs first param (related) as {object} with property id set")},removeRelation(e){e&&e.id?this.pendingRelations=this.pendingRelations.filter(t=>t.id!==e.id):console.error("[removeRelation] needs first param (related) as {object} with property id set")},removeAllRelations(){this.pendingRelations=[],this._setChildrenData(this,"stageRelated",!1)},_setChildrenData(e,t,i){void 0!==e&&t in e&&(e[t]=i),e.$children.forEach(e=>{this._setChildrenData(e,t,i)})},createTree(){let e=[];return this.objects.forEach(t=>{let i=t.meta.path&&t.meta.path.split("/");if(i.length){i.shift();let a=e;i.forEach(e=>{let i=this.findPath(a,e);if(i)a=i.children;else{let i=t;i.id!==e&&(i=this.findObjectById(e));let s={id:e,related:this.isRelated(e),name:i.attributes.title||"",object:i,children:[]};a.push(s),a=s.children}})}}),e},findPath(e,t){let i=e.filter(e=>e.id===t);return!!i.length&&i[0]},isRelated(e){return!!this.relatedObjects.filter(t=>e===t.id).length},isPending(e){return!!this.pendingRelations.filter(t=>e===t.id).length}}},v=i(3),R=i.n(v);var P={inject:["requestPanel","closePanel"],mixins:[m,{data:()=>({relationData:null,relationSchema:null}),methods:{getRelationData(){let e=window.location.href;if(this.relationName){let t=`${e}/relationData/${this.relationName}`;const i={credentials:"same-origin",headers:{accept:"application/json"}};return this.relationData?Promise.resolve(this.relationData):fetch(t,i).then(e=>e.json()).then(e=>{if(e.data&&e.data.attributes)return this.relationData=e.data.attributes,this.relationSchema=this.getRelationSchema(),this.relationData}).catch(e=>{console.error(e)})}return console.error("[RelationSchemaMixin] relationName not defined - can't load relation schema"),Promise.reject()},relationHasParams(){return null!==this.relationData&&!!this.getRelationSchema()},getRelationSchema(){return null===this.relationSchema&&(this.relationSchema=null!==this.relationData&&this.relationData.params&&this.relationData.params.properties),this.relationSchema},getParamHelper:(e,t)=>e.meta.relation.params&&e.meta.relation.params[t]||null}}],components:{StaggeredList:c,RelationshipsView:g,FilterBoxView:u,TreeView:y},props:{relationName:{type:String,required:!0},loadOnStart:[Boolean,Number],multipleChoice:{type:Boolean,default:!0},configPaginateSizes:{type:String,default:"[]"}},data:()=>({method:"relatedJson",loading:!1,count:0,removedRelated:[],addedRelations:[],modifiedRelations:[],removedRelationsData:[],addedRelationsData:[],relationsData:[],activeFilter:{}}),computed:{alreadyInView(){var e=this.addedRelations.map(e=>e.id),t=this.objects.map(e=>e.id);return e.concat(t)}},created(){this.endpoint=`${this.method}/${this.relationName}`},mounted(){this.loadOnMounted()},watch:{loading(e){this.$emit("loading",e)}},methods:{onFilterObjects(e){this.activeFilter=e,this.toPage(1,this.activeFilter)},onUpdatePageSize(e){this.setPageSize(e),this.loadRelatedObjects(this.activeFilter)},onUpdateCurrentPage(e){this.toPage(e,this.activeFilter)},async loadOnMounted(){if(this.loadOnStart){var e="number"==typeof this.loadOnStart?this.loadOnStart:0;await Object(b.a)(e),null===this.relationSchema&&await this.getRelationData(),await this.loadRelatedObjects()}},async loadRelatedObjects(e={}){this.loading=!0;let t=await this.getPaginatedObjects(!0,e);return this.loading=!1,this.$emit("count",this.pagination.count),t},relationToggle(e){e&&e.id?this.containsId(this.removedRelated,e.id)?this.restoreRemovedRelation(e):this.removeRelation(e):console.error("[relationToggle] needs first param (related) as {object} with property id set")},removeRelation(e){this.removedRelated.push(e),this.prepareRelationsToRemove(this.removedRelated),this.containsId(this.modifiedRelations,e.id)&&this.prepareRelationsToSave()},restoreRemovedRelation(e){let t=this.removedRelated.findIndex(t=>t.id!==e.id);this.removedRelated.splice(t,1),this.prepareRelationsToRemove(this.removedRelated),this.containsId(this.modifiedRelations,e.id)&&this.prepareRelationsToSave()},prepareRelationsToRemove(e){this.removedRelationsData=JSON.stringify(this.formatObjects(e)),this.$el.dispatchEvent(new Event("change",{bubbles:!0}))},setRemovedRelated(e){e&&(this.removedRelated=e,this.prepareRelationsToRemove(this.removedRelated))},removeAddedRelations(e){e?(this.addedRelations=this.addedRelations.filter(t=>t.id!==e),this.prepareRelationsToSave()):console.error("[removeAddedRelations] needs first param (id) as {Number|String}")},modifyRelation(e){this.containsId(this.modifiedRelations,e.id)?this.modifiedRelations=this.modifiedRelations.map(t=>t.id===e.id?e:t):this.modifiedRelations.push(e),this.prepareRelationsToSave()},removeModifiedRelations(e){e?(this.modifiedRelations=this.modifiedRelations.filter(t=>t.id!==e),this.prepareRelationsToSave()):console.error("[removeModifiedRelations] needs first param (id) as {Number|String}")},updateRelationParams(e){const t=e.related.id,i=this.objects.filter(e=>{if(e.id===t)return e}).pop();this.modifyRelation(i)},appendRelations(e){if(this.addedRelations.length){let t=this.addedRelations.map(e=>e.id);for(let i=0;i<e.length;i++)t.indexOf(e[i].id)<0&&this.addedRelations.push(e[i])}else this.addedRelations=e;this.prepareRelationsToSave()},prepareRelationsToSave(){let e=this.addedRelations.concat(this.modifiedRelations).filter(e=>!this.containsId(this.removedRelated,e.id));this.addedRelationsData=JSON.stringify(this.formatObjects(e)),this.$el.dispatchEvent(new Event("change",{bubbles:!0}))},formatParam(e,t){const i=this.getRelationSchema();return void 0!==i&&"date-time"===i[e].format?R.a.formatDate(new Date(t),"Y-m-d h:i K"):t},async toPage(e,t){this.loading=!0;let i=await m.methods.toPage.call(this,e,t);return this.loading=!1,i},containsId:(e,t)=>e.filter(e=>e.id===t).length,buildViewUrl:(e,t)=>`${window.location.protocol}//${window.location.host}/${e}/view/${t}`}},j={components:{PropertyView:{components:{RelationView:P,ChildrenView:{extends:P,data:()=>({positions:{}}),methods:{updatePosition(e){const t=e.meta.relation.position,i=""!==this.positions[e.id]?this.positions[e.id]:void 0;if(i!==t)try{const t=JSON.parse(JSON.stringify(e));t.meta.relation.position=i,this.modifyRelation(t)}catch(e){console.error("[ChildrenView -> updatePosition] something's wrong with the data")}else this.removeModifiedRelations(e.id)}}}},props:{tabOpen:{type:Boolean,default:!0},isDefaultOpen:{type:Boolean,default:!1}},data:()=>({isOpen:!0,isLoading:!1,count:0}),mounted(){this.isOpen=this.isDefaultOpen},watch:{tabOpen(){this.isOpen=this.tabOpen}},methods:{toggleVisibility(){this.isOpen=!this.isOpen},onToggleLoading(e){this.isLoading=e},onCount(e){this.count=e}}},RelationView:P},data:()=>({tabsOpen:!0}),computed:{keyEvents(){return{esc:{keyup:this.toggleTabs}}}},methods:{toggleTabs(){return this.tabsOpen=!this.tabsOpen}}},w={extends:d,methods:{restoreItem(){this.selectedRows.length<1||document.getElementById("form-restore").submit()},deleteItem(){this.selectedRows.length<1||confirm("Confirm deletion of "+this.selectedRows.length+" item from the trash")&&document.getElementById("form-delete").submit()}}},O={extends:j},S={data:()=>({fileName:""}),computed:{},methods:{onFileChanged(e){this.fileName=e.target.files[0].name}}},F=(i(10),{inject:["returnDataFromPanel","closePanel"],mixins:[m],components:{FilterBoxView:u},props:{relationName:{type:String,default:""},alreadyInView:{type:Array,default:()=>[]},configPaginateSizes:{type:String,default:"[]"}},data:()=>({method:"relationshipsJson",endpoint:"",selectedObjects:[],activeFilter:{},loading:!1}),watch:{relationName:{immediate:!0,handler(e,t){e&&(this.selectedObjects=[],this.endpoint=`${this.method}/${e}`,this.loadObjects()),""===e&&Object(b.a)(500).then(()=>this.objects=[])}},loading(e){this.$emit("loading",e)}},methods:{onFilterObjects(e){this.activeFilter=e,this.toPage(1,this.activeFilter)},onUpdatePageSize(e){this.setPageSize(e),this.loadObjects(this.activeFilter)},onUpdateCurrentPage(e){this.toPage(e,this.activeFilter)},paginationPageLinkVisible(e){return this.pagination.page_count<=7||(1===e||e===this.pagination.page_count||e>=this.pagination.page-1&&e<=this.pagination.page+1)},toggle(e,t){let i=this.selectedObjects.indexOf(e);-1!=i?this.selectedObjects.splice(i,1):this.selectedObjects.push(e)},async loadObjects(e){this.objects=[],this.loading=!0;let t=await this.getPaginatedObjects(!0,e);return this.loading=!1,this.$emit("count",this.pagination.count),t},async toPage(e,t){this.objects=[],this.loading=!0;let i=await m.methods.toPage.call(this,e,t);return this.loading=!1,i}}}),$={inject:["returnDataFromPanel","closePanel"],props:{relation:{type:Object,default:()=>{}}},computed:{relatedStatus(){return this.relation.related.attributes.status},relatedType(){let e="(not available)";return this.relation&&this.relation.related&&(e=this.relation.related.type),e},relatedName(){let e="(empty)";return this.relation&&this.relation.related&&(e=this.relation.related.attributes.title),e}},data:()=>({oldParams:{},editingParams:{},priority:null,isModified:!1}),watch:{relation(e){e?(Object.assign(this.oldParams,e.related.meta.relation.params),Object.assign(this.editingParams,e.related.meta.relation.params),this.priority=e.related.meta.relation.priority):(this.oldParams={},this.editingParams={},this.isModified=!1,this.priority=null)}},methods:{saveParams(){Object.keys(this.editingParams).length?this.relation.related.meta.relation.params=this.editingParams:delete this.relation.related.meta.relation.params,this.relation.related.meta.relation.priority=this.priority,this.closeParamsView(),this.returnDataFromPanel({action:"edit-params",item:this.relation})},closeParamsView(){this.editingParams={},this.closePanel()},checkParams(){this.isModified=!!Object.keys(this.editingParams).filter(e=>""!==this.editingParams[e]&&this.editingParams[e]!==this.oldParams[e]).length||this.relation.related.meta.relation.priority!==this.priority}}};i(9);const k={enableTime:!0,dateFormat:"Y-m-d H:i",altInput:!0,altFormat:"F j, Y - H:i",animate:!1},I={enableTime:!1,dateFormat:"Y-m-d",altInput:!0,altFormat:"F j, Y",animate:!1};var T={install(e){e.directive("datepicker",{inserted(e,t,i){let a=I;i.data&&i.data.attrs&&"true"===i.data.attrs.time&&(a=k);try{let t=R()(e,a),i=document.createElement("span");i.classList.add("clear-button"),i.innerHTML="&times;",i.addEventListener("click",()=>{t.clear()}),e.parentElement.appendChild(i)}catch(e){console.error(e)}}})}},E=i(5),x=i.n(E);i(8);const B={mode:"code",modes:["tree","code"],history:!0,search:!0};var D={install(e){e.directive("jsoneditor",{inserted(e){const t=e.value;try{const i=JSON.parse(t)||{};if(i){e.style.display="none";let t=document.createElement("div");t.className="jsoneditor-container",e.parentElement.insertBefore(t,e);let a=Object.assign(B,{onChange:function(){try{const t=e.jsonEditor.get();e.value=JSON.stringify(t),console.info("valid json :)")}catch(e){console.warn("still not valid json")}}});e.jsonEditor=new x.a(t,a),e.jsonEditor.set(i)}}catch(e){console.error(e)}}})}},z={install(e){e.directive("richeditor",{inserted(e){const t=e.getAttribute("ckconfig");let i={};r&&(i=r[t]);let a=CKEDITOR.replace(e,i);a.on("change",()=>{e.value=a.getData(),e.dispatchEvent(new Event("change",{bubbles:!0}))})}})}},C=i(4),L=i.n(C);const N=new s.a({el:"main",components:{ModulesIndex:d,ModulesView:j,TrashIndex:w,TrashView:O,ImportView:S,RelationsAdd:F,FilterBoxView:u,EditRelationParams:$},data:()=>({vueLoaded:!1,urlPagination:"",searchQuery:"",pageSize:"100",page:"",sort:"",panelIsOpen:!1,addRelation:{},editingRelationParams:null,urlFilterQuery:{q:""},pagination:{page:"",page_size:"100"}}),provide(){return{requestPanel:(...e)=>this.requestPanel(...e),closePanel:(...e)=>this.closePanel(...e),returnDataFromPanel:(...e)=>this.returnDataFromPanel(...e)}},beforeCreate(){s.a.use(D),s.a.use(T),s.a.use(z),s.a.use(L.a),l.a.loadBeditaPlugins()},created(){this.vueLoaded=!0,this.loadUrlParams()},watch:{panelIsOpen(e){var t=document.querySelector("html").classList;e?t.add("is-clipped"):t.remove("is-clipped")},pageSize(e){this.pagination.page_size=e}},mounted:function(){this.$nextTick(function(){"view"==BEDITA.template&&this.alertBeforePageUnload()})},methods:{onFilterObjects(e){this.urlFilterQuery=e,this.page="",this.applyFilters(this.urlFilterQuery)},onUpdatePageSize(e){this.pageSize=e,this.page="",this.applyFilters(this.urlFilterQuery)},onUpdateCurrentPage(e){this.page=e,this.applyFilters(this.urlFilterQuery)},pageClick(e){},returnDataFromPanel(e){if(this.closePanel(),"add-relation"===e.action&&this.$refs.moduleView.$refs[e.relationName].$refs.relation.appendRelations(e.objects),"edit-params"===e.action){const t=e.item.name;this.$refs.moduleView.$refs[t].$refs.relation.updateRelationParams(e.item)}},closePanel(){this.panelIsOpen=!1,this.addRelation={name:"",alreadyInView:[]},this.editingRelationParams=null},requestPanel(e){this.panelIsOpen=!0,this.panelIsOpen&&e.relation&&e.relation.name?this.addRelation=e.relation:this.panelIsOpen&&e.editRelationParams&&e.editRelationParams.name&&(this.editingRelationParams=e.editRelationParams)},loadUrlParams(){if(window.location.search){const e=window.location.search,t=/[?&]q=([^&#]*)/g;let i=e.match(t);i&&i.length&&(i=i.map(e=>e.replace(t,"$1")),this.urlFilterQuery={q:i[0]});const a=/[?&]page_size=([^&#]*)/g;(i=e.match(a))&&i.length&&(i=i.map(e=>e.replace(a,"$1")),this.pageSize=this.isNumeric(i[0])?i[0]:"");const s=/[?&]page=([^&#]*)/g;(i=e.match(s))&&i.length&&(i=i.map(e=>e.replace(s,"$1")),this.page=this.isNumeric(i[0])?i[0]:"");const n=/[?&]sort=([^&#]*)/g;(i=e.match(n))&&i.length&&(i=i.map(e=>e.replace(n,"$1")),this.sort=i[0])}},buildUrlParams(e){let t=`${window.location.origin}${window.location.pathname}`,i=!0;return Object.keys(e).forEach(a=>{e[a]&&""!==e[a]&&(t+=`${i?"?":"&"}${a}=${e[a]}`,i=!1)}),t},resetFilters(){this.page="",this.pageSize=100;this.applyFilters({q:""})},applyFilters(e){let t=this.buildUrlParams({q:e.q,page_size:this.pageSize,page:this.page,sort:this.sort});window.location.replace(t)},alertBeforePageUnload(){var e=[...document.querySelectorAll("form")];e.forEach(e=>{e.addEventListener("change",()=>{e.changed=!0}),e.addEventListener("submit",t=>{!e.action.endsWith("/delete")||confirm("Do you really want to trash the object?")?e.submitting=!0:t.preventDefault()})}),window.onbeforeunload=function(){if(e.some(e=>e.changed)&&!e.some(e=>e.submitting))return"There are unsaved changes, are you sure you want to leave page?"}},isNumeric:e=>!isNaN(e)}});window._vueInstance=N}},[[15,0,1]]]);