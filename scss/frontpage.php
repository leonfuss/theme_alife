// ALIFE Theme SCSS

// Colour palette from your design
:root {
    --alife-purple: #9b7cb8;
    --alife-green: #6b8e23;
    --alife-light-green: #e8f5e8;
    --alife-light-purple: #f5f2f8;
    --alife-dark-purple: #6b4c7a;
    --alife-white: #ffffff;
    --alife-grey: #f8f9fa;
}

// Override Moodle's default variables
$primary: var(--alife-green);
$purple: var(--alife-purple);

// Global styles
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    background-color: var(--alife-grey);
}

// Header customisation
.navbar-brand {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--alife-green) !important;
}

// Custom frontpage styles
.alife-frontpage {
    background: linear-gradient(135deg, var(--alife-white) 0%, var(--alife-light-purple) 100%);
    min-height: 100vh;
    
    .alife-header {
        background: var(--alife-white);
        padding: 1rem 0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        
        .alife-logo {
            width: 60px;
            height: 60px;
            background: var(--alife-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .alife-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
            
            a {
                text-decoration: none;
                color: var(--alife-green);
                font-weight: 500;
                padding: 0.5rem 1rem;
                border-radius: 25px;
                transition: all 0.3s ease;
                
                &:hover {
                    background: var(--alife-light-green);
                }
                
                &.login-btn {
                    background: var(--alife-green);
                    color: white;
                    
                    &:hover {
                        background: var(--alife-dark-purple);
                    }
                }
            }
        }
    }
    
    .alife-hero {
        padding: 4rem 0;
        
        .alife-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
            
            .alife-text {
                color: var(--alife-green);
            }
            
            .life-text {
                background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, var(--alife-purple));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        }
        
        .alife-subtitle {
            font-size: 1.25rem;
            color: #666;
            margin-bottom: 3rem;
        }
    }
    
    .alife-courses {
        margin-bottom: 4rem;
        
        .section-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--alife-green);
            margin-bottom: 2rem;
        }
        
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            
            .course-card {
                background: white;
                border-radius: 20px;
                overflow: hidden;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                cursor: pointer;
                
                &:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
                }
                
                .course-image {
                    height: 200px;
                    background-size: cover;
                    background-position: center;
                    position: relative;
                    
                    .course-number {
                        position: absolute;
                        bottom: 1rem;
                        left: 1rem;
                        background: var(--alife-green);
                        color: white;
                        width: 50px;
                        height: 50px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.5rem;
                        font-weight: bold;
                    }
                }
                
                .course-content {
                    padding: 1.5rem;
                    
                    .course-title {
                        font-size: 1.25rem;
                        font-weight: 600;
                        color: var(--alife-green);
                        margin-bottom: 0.5rem;
                    }
                    
                    .course-description {
                        color: #666;
                        line-height: 1.6;
                    }
                }
            }
        }
    }
    
    .alife-features {
        background: var(--alife-light-purple);
        padding: 3rem 0;
        border-radius: 30px;
        margin: 3rem 0;
        
        .features-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 600;
            color: var(--alife-purple);
            margin-bottom: 2rem;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            
            .feature-item {
                text-align: center;
                
                .feature-icon {
                    width: 60px;
                    height: 60px;
                    background: var(--alife-purple);
                    border-radius: 50%;
                    margin: 0 auto 1rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-size: 1.5rem;
                }
                
                .feature-title {
                    font-size: 1.25rem;
                    font-weight: 600;
                    color: var(--alife-purple);
                    margin-bottom: 0.5rem;
                }
            }
        }
    }
    
    .alife-footer {
        background: var(--alife-purple);
        color: white;
        padding: 2rem 0;
        margin-top: 4rem;
        
        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            
            .footer-links {
                display: flex;
                gap: 2rem;
                
                a {
                    color: white;
                    text-decoration: none;
                    
                    &:hover {
                        text-decoration: underline;
                    }
                }
            }
            
            .alife-brand {
                font-size: 2rem;
                font-weight: bold;
            }
        }
    }
}

// Hide default Moodle elements on frontpage
body.path-site.frontpage {
    #page-header,
    .navbar,
    #nav-drawer {
        display: none !important;
    }
    
    #page {
        padding-top: 0 !important;
    }
}
