<x-layouts-main>
    <div class="lg:w-4/5 lg:mx-auto mt-3">
        <div class="relative p-4 bg-secondary-50 rounded-lg shadow dark:bg-secondary-100 sm:p-5">
            <div class="flex items-center justify-between pb-4 mb-4 border-b rounded-t sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Add Product
                </h3>
            </div>
            <form action="{{ route('resource.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type product name" required>
                    </div>
                    <div>
                        <label for="price"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Price</label>
                        <input type="number" name="price" id="price"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="$9" required step="0.1" value="{{ old('price')}}">
                    </div>
                    <div>
                        <label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Category</label>
                        <select id="category" required name="type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                            <option value="addons">Addons</option>
                            <option value="theme">Theme</option>
                        </select>
                    </div>
                    <div>
                        <label for="image"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Image</label>
                        <input type="file" name="image" id="image" accept="image/png, image/jpeg"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required value="{{ old('image') }}">
                    </div>
                    <!-- File Input -->
                    <div class="sm:col-span-2">
                        <label for="file"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File</label>
                        <input type="file" name="file" id="file" accept="application/zip"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required value="{{ old('file') }}">
                    </div>
                    <div class="sm:col-span-2">
                        <!--slogan-->
                        <label for="slogan"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Slogan</label>
                        <input type="text" name="slogan" id="slogan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type product slogan" required value="{{ old('slogan') }}">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea id="description" rows="4" required name="description"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Write product description here">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="w-6 h-6 mr-1 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Add new product
                </button>
            </form>
        </div>
        <script>
            document.getElementById('name').addEventListener('input', function(e) {
                document.getElementById('productName').innerHTML = this.value;
            });
            document.getElementById('slogan').addEventListener('input', function() {
                document.getElementById('productSlogan').innerHTML = this.value;
            });
            document.getElementById('price').addEventListener('input', function() {
                document.getElementById('productPrice').innerHTML = this.value;
            });
            document.getElementById('image').addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('productImage').src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        </script>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <caption
                class="p-5 text-lg font-semibold text-left text-gray-900 bg-secondary-50 dark:text-white dark:bg-secondary-100">
                This is how your products will be
                displayed on our website.
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Start filling out the form to see
                    it update!</p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="">
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Resource
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stats
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    class="text-white bg-secondary-50 border-b dark:bg-secondary-100 dark:border-gray-700 hover:dark:border-gray-800 hover:dark:bg-gray-900">
                    <td class="flex items-center w-1/3 px-6 py-4">
                        <img src=""
                            onerror="this.onerror=null;this.src='/img/logo.png';" class="w-10 h-10 rounded-md"
                            id="productImage" alt="Image">
                        <p class="pl-1 text-sm font-medium text-gray-900 dark:text-white" id="productName"> </p>

                    </td>
                    <td class="w-1/3 px-6 py-4 text-gray-900 dark:text-white">
                        <span id="productSlogan"></span>
                        <br>
                        {{ auth()->user()->username }} • <span class="text-xs text-gray-500">$</span><span
                            class="text-xs text-gray-500" id="productPrice"></span>
                    </td>
                    <td class="w-1/3 px-6 py-4 text-gray-900 dark:text-white">
                        Not yet available
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-layouts-main>
