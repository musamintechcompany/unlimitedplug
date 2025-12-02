<x-guest1-layout>
    <x-slot name="title">Software - Unlimited Plug</x-slot>
    <x-slot name="description">Premium software solutions for business and personal use. Buy or rent the tools you need.</x-slot>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Premium Software Solutions</h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto opacity-90">
                Discover professional software tools for business and personal use. Buy or rent the applications you need to boost your productivity.
            </p>
        </div>
    </section>

    <!-- Software Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div id="software-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Software items will be loaded here -->
        </div>
    </div>

    <script>
        const softwareProducts = [
            {
                id: 1,
                title: "Adobe Photoshop 2024",
                description: "Professional photo editing and graphic design software with AI-powered features.",
                price: 299,
                rentPrice: 29,
                rating: 4.8,
                reviews: 156,
                image: "https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=300&fit=crop"
            },
            {
                id: 2,
                title: "Microsoft Office 365",
                description: "Complete productivity suite with Word, Excel, PowerPoint, and cloud storage.",
                price: 149,
                rentPrice: 15,
                rating: 4.6,
                reviews: 89,
                image: "https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=400&h=300&fit=crop"
            },
            {
                id: 3,
                title: "AutoCAD 2024",
                description: "Industry-leading CAD software for 2D and 3D design and drafting.",
                price: 1899,
                rentPrice: 189,
                rating: 4.7,
                reviews: 67,
                image: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=400&h=300&fit=crop"
            },
            {
                id: 4,
                title: "Slack Business+",
                description: "Team collaboration platform with advanced security and admin features.",
                price: 99,
                rentPrice: 12,
                rating: 4.5,
                reviews: 234,
                image: "https://images.unsplash.com/photo-1611224923853-80b023f02d71?w=400&h=300&fit=crop"
            },
            {
                id: 5,
                title: "Final Cut Pro",
                description: "Professional video editing software for Mac with advanced color grading.",
                price: 399,
                rentPrice: 39,
                rating: 4.9,
                reviews: 78,
                image: "https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=400&h=300&fit=crop"
            },
            {
                id: 6,
                title: "Figma Pro",
                description: "Collaborative interface design tool with advanced prototyping features.",
                price: 144,
                rentPrice: 15,
                rating: 4.8,
                reviews: 145,
                image: "https://images.unsplash.com/photo-1561070791-2526d30994b5?w=400&h=300&fit=crop"
            }
        ];

        document.addEventListener('DOMContentLoaded', () => {
            renderSoftware(softwareProducts);
        });

        function renderSoftware(products) {
            const grid = document.getElementById('software-grid');
            grid.innerHTML = '';
            
            products.forEach(product => {
                const card = createSoftwareCard(product);
                grid.appendChild(card);
            });
        }

        function createSoftwareCard(product) {
            const card = document.createElement('div');
            card.className = 'cursor-pointer border border-gray-200 dark:border-gray-700 overflow-hidden';
            
            const starsHTML = createStarsHTML(product.rating);
            
            card.innerHTML = `
                <div class="relative mb-3">
                    <img src="${product.image}" alt="${product.title}" class="w-full h-40 object-cover">
                </div>
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Software</span>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mt-1 mb-2">${product.title}</h3>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">${product.description}</p>
                <div class="flex items-center mb-3">
                    ${starsHTML}
                    <span class="text-sm text-gray-500 ml-1">(${product.reviews})</span>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-lg font-bold text-blue-600">$${product.price}</div>
                        <div class="text-sm text-gray-500">or $${product.rentPrice}/month</div>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">Buy</button>
                        <button class="px-3 py-1 border border-gray-300 text-gray-700 text-sm rounded hover:bg-gray-50">Rent</button>
                    </div>
                </div>
            `;
            
            return card;
        }

        function createStarsHTML(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 >= 0.5;
            let starsHTML = '';
            
            for (let i = 0; i < fullStars; i++) {
                starsHTML += '<span class="text-yellow-400">★</span>';
            }
            
            if (hasHalfStar) {
                starsHTML += '<span class="text-yellow-400">☆</span>';
            }
            
            const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
            for (let i = 0; i < emptyStars; i++) {
                starsHTML += '<span class="text-gray-300">☆</span>';
            }
            
            return starsHTML;
        }
    </script>
</x-guest1-layout>